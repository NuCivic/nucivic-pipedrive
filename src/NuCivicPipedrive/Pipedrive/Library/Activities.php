<?php

namespace NuCivicPipedrive\Pipedrive\Library;

class Activities extends APIObject
{

    public $fields;
    public $typesEndpoint;

    /**
     * [__construct description]
     * @param [type] $http [description]
     */
    public function __construct($http)
    {
        parent::__construct($http);
        $this->filter = array();
        $this->endpoint = '/activities';
        $this->typesEndpoint = '/activityTypes';
    }

    public function getAll()
    {
        $users = $this->http->get('/users:(id)');
        $users = $this->safeReturn($users);

        $data = array();
        foreach($users as $user) {
            $args = array('user_id' => $user->id);
            $items = $this->http->get($this->endpoint, $args);
            if (!empty($items->data)) {
                $data = array_merge($data, $this->safeReturn($items));
            }

            $pagination = $items->additional_data->pagination;
            if ($pagination->more_items_in_collection == 1) {
                do {
                    $args = array('start' => $pagination->next_start, 'user_id' => $user->id);
                    $pass = $this->http->get($this->endpoint, $args);
                    $data = array_merge($data, $this->safeReturn($pass));
                    $pagination = $pass->additional_data->pagination;
                }
                while ($pagination->more_items_in_collection == 1);
            }
        }
        return $data;
    }

    public function cleanData(&$data) {
        // Replace referral options with string values
        parent::cleanData($data);
    }

}
