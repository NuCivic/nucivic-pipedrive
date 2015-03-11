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
    public $fields_keep;
    public $endpoint;

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
        $deals = $this->http->get($this->endpoint);
        $data = $this->safeReturn($deals);

        $pagination = $deals->additional_data->pagination;

        $accepted_params = array('start', 'limit');
        if ($pagination->more_items_in_collection == 1) {
            do {
                $args = array('start' => $pagination->next_start);
                $query_string = $this->http->buildQueryString($args, $accepted_params);
                $pass = $this->http->getWithParams($this->endpoint . '?' . $query_string);
                $data = array_merge($data, $this->safeReturn($pass));
                $pagination = $pass->additional_data->pagination;
            }
            while ($pagination->more_items_in_collection == 1);
        }
        return $data;
    }

}
