<?php

/**
 * Work, Mobile, Home, Fax
 */

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
                'work_phone',
                'mobile_phone',
                'home_phone',
                'fax',
                'work_email',
                'home_email',
                'other_email',
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
            // Array of translations from labels to export columns
            'phone_label_translate' => array(
                'home' => 'home_phone',
                'Fax' => 'fax',
                'mobile' => 'mobile_phone',
                'Mobile' => 'mobile_phone',
                'work_fax' => 'fax',
                'home_fax' => 'fax',
            ),
            'email_label_translate' => array(
                'other' => 'other_email',
                'home' => 'home_email',
            ),
        );
        $this->endpoint = '/persons';
        $this->fieldsEndpoint = '/personFields';
    }

    public function cleanData(&$data) {
        foreach($data as $row) {
            $row->work_phone = $row->mobile_phone = $row->home_phone = $row->fax = NULL;
            $row->work_email = $row->home_email = $row->other_email = NULL;
            foreach($row->phone as $phone) {
                // There could be multiple work_phone values, so start with
                // an array that we can implode.
                $work_phone = array();
                if ($phone->value) {
                    // If there is a phone value, match it to the right export column
                    if (isset($phone->label) && in_array($phone->label, array_keys($this->filter['phone_label_translate']))) {
                        $phone_field = $this->filter['phone_label_translate'][$phone->label];
                        $row->$phone_field = $phone->value;
                    }
                    // Anything else is a work phone
                    else {
                        $work_phone[] = $phone->value;
                    }
                }
                if (!empty($work_phone)) {
                    $row->work_phone = implode(';', $work_phone);
                }
            }
            foreach($row->email as $email) {
                // There could be multiple work_phone values, so start with
                // an array that we can implode.
                $work_email = array();
                if ($email->value) {
                    // If there is a phone value, match it to the right export column
                    if (isset($email->label) && in_array($email->label, array_keys($this->filter['email_label_translate']))) {
                        $email_field = $this->filter['email_label_translate'][$email->label];
                        $row->$email_field = $email->value;
                    }
                    // Anything else is a work phone
                    else {
                        $work_email[] = $email->value;
                    }
                }
                if (!empty($work_email)) {
                    $row->work_email = implode(';', $work_email);
                }
            }

        }
        parent::cleanData($data);
    }

}
