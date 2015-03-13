<?php

namespace NuCivicPipedrive\Pipedrive\Library;

class Products extends APIObject
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
                'name',
                'owner_id',
                'code',
                'selectable',
                'visible_to',
                'price',
                'deal_id',
            ),
        );

        $this->endpoint = '/products';
        $this->fieldsEndpoint = '/productFields';
    }
}
