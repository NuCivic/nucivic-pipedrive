<?php

namespace NuCivicPipedrive\Pipedrive\Library;

class Notes extends APIObject
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
              'content',
              'user_id',
              'deal_id',
              'person_id',
              'org_id',
              'content',
              'add_time',
              'update_time',
            ),
        );

        $this->endpoint = '/notes';
    }
}
