<?php

namespace NuCivicPipedrive\Pipedrive\Library;

use NuCivicPipedrive\Pipedrive\Exceptions\ValidatorException;
use NuCivicPipedrive\Pipedrive\Exceptions\PipedriveException;

use Zend\Validator\Digits;

abstract class APIObject
{
    /**
     * Fields to keep for CSV export
     * @var array
     */
    public $filter;

    /**
     * Base url for data endpoint
     * @var [type]
     */
    public $endpoint;

    /**
     * Base url for data endpoint
     * @var [type]
     */
    public $fieldsEndpoint;

    /**
     * [$http description]
     * @var [type]
     */
    protected $http;

    /**
     * [__construct description]
     * @param [type] $http [description]
     */
    protected function __construct($http)
    {
        $this->http = $http;
        $this->filter = array();
        $this->endpoint = NULL;
    }

    /**
     * Handles errors in Pipedrive API requests.
     *
     * @param array $data JSON object.
     *
     * @throws PipedriveException if $data->success isn't there.
     * @return mixed Return data.
     */
    protected static function safeReturn($data)
    {
        if (!$data->success) {
            throw new PipedriveException(isset($data->error) ? "Pipedrive: " . $data->error : "Unknown error.");
        } else {
            return $data->data;
        }
    }

    /**
     * Validates if input is a number.
     *
     * @param $input mixed Input for validator.
     * @return bool Returns true if validation passed.
     * @throws ValidatorException if $input is not a number.
     */
    protected function validateDigit($input)
    {
        $validator = new Digits();
        if (!$validator->isValid($input)) {
            throw new ValidatorException("Validation failed: {$input} is not a digit in function " .
                debug_backtrace()[1]['function'] . ".");
        }
        return true;
    }

    /**
     * Iterate through Pipedrive pagination to retrieve all api objects.
     *
     */
    public function getAll()
    {
        $items = $this->http->get($this->endpoint);
        $data = $this->safeReturn($items);

        $pagination = $items->additional_data->pagination;

        if ($pagination->more_items_in_collection == 1) {
            do {
                $args = array('start' => $pagination->next_start);
                $pass = $this->http->get($this->endpoint, $args);
                $data = array_merge($data, $this->safeReturn($pass));
                $pagination = $pass->additional_data->pagination;
            }
            while ($pagination->more_items_in_collection == 1);
        }
        return $data;
    }

    /**
     * Get all fields.
     *
     * @return array Array of all person field objects.
     */
    public function getFields()
    {
        if (!$this->fieldsEndpoint) {
            return array();
        }
        $data = $this->http->get($this->fieldsEndpoint);
        return $this->safeReturn($data);
    }

    public function translateFieldKeys(&$row, array $fields)
    {
        foreach ($row as $key => $value) {
            if ($this->isCustomField($key)) {
                $field = $this->getFieldByKey($key, $fields);
                if ($field) {
                    $name = $field->name;
                    $row->$name = $row->$key;
                    unset($row->$key);
                }
            }
        }
    }

    /**
     * Checks if key is a custom field.
     *
     * Checks for: 40 characters, loweralpha + numeric.
     *
     * @param string $key Key of Person field.
     * @return boolean True if $key is custom Person field.
     */
    public function isCustomField($key)
    {
        return preg_match('/^[a-f0-9]{40}$/', $key);
    }

    /**
     * Translate custom field key to text.
     *
     * @param string $key Key of field.
     * @param object $fields Custom Fields to look through (output of getFields()).
     * @return string Field text that belongs to key.
     */
    public function getFieldByKey($key, array $fields)
    {
        foreach ($fields as $field) {
            if ($field->key == $key) {
                return $field;
            }
        }

        return null;
    }

    /**
     * [cleanData description]
     * @param  [type] $filter Array of filters to apply to the object's data property
     */
    public function cleanData(&$data) {
        $raw = $data;
        $data = array();
        $fields = $this->getFields();
        foreach($raw as $key => $row) {
            $this->translateFieldKeys($row, $fields);
            $data[$key] = get_object_vars($row);
        }
        if (!empty($this->filter['fields_keep'])) {
            foreach($data as $rownum => $row) {
                foreach($row as $field => $value) {
                    if (!in_array($field, $this->filter['fields_keep'])) {
                        unset($data[$rownum][$field]);
                    }
                    elseif (is_object($value) && isset($value->value)) {
                        $data[$rownum][$field] = $value->value;
                    }
                    elseif (is_array($value) && is_object(current($value))) {
                        $multival = array();
                        foreach ($value as $subvalue) {
                            $multival[] = $subvalue->value;
                        }
                        $data[$rownum][$field] = implode(';', $multival);
                    }
                }
            }
        }
    }

}
