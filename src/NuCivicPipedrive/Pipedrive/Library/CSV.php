<?php

namespace NucivicPipedrive\Pipedrive\Library;
use Goodby\CSV\Export\Standard\CsvFileObject;
use Goodby\CSV\Export\Standard\Exporter;
use Goodby\CSV\Export\Standard\ExporterConfig;

class CSV
{
  private $data;

  /**
   * [__construct description]
   * @param array $data Take an array of rows of data.
   */
  public function __construct(array $data)
  {
    $this->data = $data;
  }

  public function write($filename)
  {
    $path = 'export/' . $filename;
    // temporary - remove objects
    foreach($this->data as $rownum => $row) {
      foreach($row as $field => $value) {
        if (is_object($value)) {
          $this->data[$rownum][$field] = '';
        }
      }
    }
    $config = new ExporterConfig();
    $exporter = new Exporter($config);
    $config
        ->setFromCharset('UTF-8')
        ->setFileMode(CsvFileObject::FILE_MODE_WRITE)
        ->setColumnHeaders(array_keys($this->data[0]));
    ;

    $exporter->export($path, array());

    // Remove column headers, and append rows now as we iterate.
    $config->setColumnHeaders(array())
        ->setFileMode(CsvFileObject::FILE_MODE_APPEND);

    $exporter->export($path, $this->data);
  }

  /**
   * [cleanData description]
   * @param  [type] $filter Array of filters to apply to the object's data property
   */
  public function cleanData($filter) {
    if (!empty($filter['fields_keep'])) {
      foreach($this->data as $rownum => $row) {
        foreach($row as $field => $value) {
          if ($row['id'] > 270 && $row['id'] < 275) {
            // print_r($row);
          }
          if (!in_array($field, $filter['fields_keep'])) {
            // unset($this->data[$rownum][$field]);
          }
          if (is_object($value)) {
            $this->data[$rownum][$field] = $value->value;
          }
        }
      }
    }
  }

}
