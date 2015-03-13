<?php

namespace NuCivicPipedrive\Pipedrive\Library;

class Organizations extends APIObject
{

    public $fields;

    /**
     * [__construct description]
     * @param [type] $http [description]
     */
    public function __construct($http)
    {
        parent::__construct($http);
        $this->filter = array(
            'fields_keep' => array(
                'id',
                'name',
                'Website',
                'owner_id',
                'people_count',
                'open_deals_count',
                'add_time',
                'update_time',
                'visible_to',
                'next_activity_date',
                'last_activity_date',
                'address',
                'Org referral source',
                'NuCivic account rep'
            ),
            'referral_options' => array(),
        );
        $this->endpoint = '/organizations';
        $this->fieldsEndpoint = '/organizationFields';
    }

    public function cleanData(&$data)
    {
        // Replace referral options with string values
        parent::cleanData($data);
    }

    protected function getTypes()
    {
        $data = $this->http->get($this->typesEndpoint);
        return $this->safeReturn($data);
    }

}
