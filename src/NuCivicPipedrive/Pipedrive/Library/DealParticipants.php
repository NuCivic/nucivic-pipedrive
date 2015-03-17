<?php

namespace NuCivicPipedrive\Pipedrive\Library;

class DealParticipants extends APIObject
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
                "added_by_user_id",
                "add_time",
                "active_flag",
                "person_id",
                "deal_id",
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
            $count = is_object($deal) ? $deal->participants_count : $deal['participants_count'];
            if ($count) {
                $participants = $this->http->get('/deals/' . $dealId . '/participants');
                $participants =$this->safeReturn($participants);
                $data = array_merge($data, $participants);
            }
        }
        return $data;
    }

    /**
     * [cleanData description]
     * @param  [type] $filter Array of filters to apply to the object's data property
     */
    public function cleanData(&$data) {
        foreach($data as $key => $row) {
            $data[$key]->deal_id = $row->related_item_data->deal_id;
            $data[$key]->person_id = $row->person->id;
        }
        parent::cleanData($data);
    }

}
