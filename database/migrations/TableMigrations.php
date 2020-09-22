<?php

namespace Migrations;

use Controllers\BaseController;
use Exception;

class TableMigrations extends BaseController
{

    private $conncetionInstance;

    public function __construct($conncetionInstance)
    {
        $this->conncetionInstance = $conncetionInstance;
    }

    public function migrateStaffTables()
    {
        try {
            $statements = [
                'CREATE TABLE IF NOT EXISTS employees (
            employee_id   INTEGER PRIMARY KEY AUTOINCREMENT,
            first_name VARCHAR (255) NOT NULL,
            last_name VARCHAR (255) NOT NULL,
            middle_name VARCHAR (255) NOT NULL,
            start_date TEXT,
            is_at_company BOOLEAN DEFAULT false,
            created_on DEFAULT CURRENT_TIMESTAMP
          )',
                'CREATE TABLE IF NOT EXISTS emp_designations (
        designation_id INTEGER PRIMARY KEY AUTOINCREMENT,
        description  VARCHAR (255) NOT NULL,
        employee_id INTEGER,
        FOREIGN KEY (employee_id)
        REFERENCES employees(employee_id) ON UPDATE CASCADE
                                        ON DELETE CASCADE)'
            ];

            foreach ($statements as $statement) {
                $this->conncetionInstance->exec($statement);
            }
            return $this->sendResponse(true, "Successfully Created Tables");
        } catch (Exception $ex) {
            error_log($ex->getMessage());
        }
    }
}
