<?php

namespace Controllers;

require './vendor/autoload.php';

use Controllers\BaseController;
use Exception;

/**
 * Read any CSV from here
 */
class ReadCSVController
{
    private $location;
    private $response = [];

    public function __construct($location)
    {
        $this->location = $location;
    }

    public function readCSV()
    {
        try {
            $file = file($this->location);
            foreach ($file as $record) {
                $this->response[] = str_getcsv($record);
            }
            return json_encode($this->response);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
