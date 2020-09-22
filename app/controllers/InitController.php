<?php

namespace Controllers;

require './vendor/autoload.php';

use Config\Constants;
use Controllers\BaseController;
use Config\InitDatabase;
use Migrations\TableMigrations;
use Exception;
use Seeders\StaffSeeder;

/**
 * App Initializing Class, Sets Up DataBase including Seeding available data
 */
class InitController extends BaseController
{
    public function index()
    {
        try {
            $connection = $this->getConnection();
            if ($connection != null) {
                $migration = new TableMigrations($connection);
                $migration->migrateStaffTables();
                $seeds = new StaffSeeder($connection);
                $seeds->migrateStaffData();
            } else {
                echo $this->sendResponse(false, "Unable to establish connection", 400);
            }

            echo $this->sendResponse(
                true,
                'App Inistialization complete',
                200
            );
        } catch (Exception $ex) {
            echo $this->sendResponse(
                false,
                'Failed to init db',
                400
            );
        }
    }
}
