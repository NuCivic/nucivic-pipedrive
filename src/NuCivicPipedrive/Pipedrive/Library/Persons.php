<?php

namespace NuCivicPipedrive\Pipedrive\Library;

class Persons extends APIObject
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
                'phone',
                'email',
                'add_time',
                'update_time',
                'Title',
                'org_id',
                'owner_id',
                'open_deals_count',
                'visible_to',
                'next_activity_date',
                'last_activity_date',
                'Referral source',
                'im',
                'postal_address',
            ),
            'referral_options' => array(),
        );
        $this->endpoint = '/persons';
        $this->fieldsEndpoint = '/personFields';
    }

    public function cleanData(&$data) {
        parent::cleanData($data);
    }

}
