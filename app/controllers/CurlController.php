<?php

namespace Controllers;

require './vendor/autoload.php';

use Controllers\BaseController;
use Config\InitDatabase;
use Migrations\TableMigrations;
use Exception;

/**
 * Make all api requests by createing an instance of the Curl Controller
 */
class CurlController
{
    private $url;
    private $method;
    private $response;

    public function __construct($url, $method = 'GET')
    {
        $this->url = $url;
        $this->method = $method;
    }

    public function makeCurlRequest()
    {
        try {
            $ch = curl_init($this->url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $this->response = curl_exec($ch);
            curl_close($ch);
            return json_encode($this->response);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
