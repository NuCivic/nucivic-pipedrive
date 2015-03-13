<?php

namespace NuCivicPipedrive\Pipedrive\Library;

class Deals extends APIObject
{
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
                'title',
                'org_id',
                'user_id',
                'value',
                'Price breakdown',
                'Estimated win probability (%)',
                'Production start date',
                'Production end date',
                'Preliminary hours estimate',
                'Deal originator',
                'person_id',
            ),
        );

        $this->endpoint = '/deals';
        $this->fieldsEndpoint = '/dealFields';
    }
}
