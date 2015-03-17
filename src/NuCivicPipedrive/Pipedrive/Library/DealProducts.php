<?php

namespace NuCivicPipedrive\Pipedrive\Library;

class DealProducts extends APIObject
{

    /**
     * The DealParticipants
     * @param [type] $http [description]
     */
    public function __construct($http)
    {
        parent::__construct($http);

        $this->filter = array(
            'fields_keep' => array(
                "id",
                "deal_id",
                "order_nr",
                "product_id",
                "product_variation_id",
                "item_price",
                "discount_percentage",
                "duration",
                "sum_no_discount",
                "sum",
                "currency",
                "enabled_flag",
                "active_flag",
                "add_time",
                "comments",
                "quantity",
            ),
        );
        // $this->endpoint = '/deals/:dealId/participants';        // $this->fieldsEndpoint = '/dealFields';
    }

    /**
     * In this case, getAll() takes an argument, and gets all
     * participant data for an array of deal objects.
     *
     * @param  array $dealData Result of Deals::getAll()
     * @return array           The resulting data
     */
    public function getAll($dealData = array()) {
        $data = array();
        foreach ($dealData as $deal) {
            $dealId = is_object($deal) ? $deal->id : $deal['id'];
            $count = is_object($deal) ? $deal->products_count : $deal['products_count'];
            if ($count) {
                $products = $this->http->get('/deals/' . $dealId . '/products');
                $products =$this->safeReturn($products);
                $data = array_merge($data, $products);
            }
        }
        return $data;
    }

    /**
     * [cleanData description]
     * @param  [type] $filter Array of filters to apply to the object's data property
     */
    public function cleanData(&$data) {
        parent::cleanData($data);
    }

}
