<?php

namespace Config;

use Controllers\BaseController;
use Migrations\TableMigrations;

use Exception;

/**
 * Initialize Database Connection Here.
 * Database connection properties can be set in the .env file
 */
class InitDatabase extends BaseController
{
    private $initializeDb;

    function establishCOnnection()
    {
        try {
            if ($this->initializeDb == null) {
                $this->initializeDb = new \PDO("sqlite:" . Constants::DB_LOCATION);
            }
            return $this->initializeDb;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
