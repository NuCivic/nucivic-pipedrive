<?php

namespace NuCivicPipedrive\Pipedrive\Library;

class Files extends APIObject
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
              'user_id',
              'deal_id',
              'person_id',
              'org_id',
              'email_message_id',
              'activity_id',
              'file_name',
              'file_type',
              'file_size',
              'remote_location',
              'remote_id',
              'deal_name',
              'person_name',
              'org_name',
              'product_name',
              'url',
              'name',
            ),
        );

        $this->endpoint = '/files';
    }

    public function downloadFiles(array $data) {
      foreach($data as $file) {
        if (!file_exists('export/files/' . $file['name'])) {
          $this->http->downloadAuth($file['url'], 'export/files/' . $file['name']);
        }
      }
    }
}
