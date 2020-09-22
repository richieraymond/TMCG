<?php

namespace Migrations;

class TableMigrations
{

    private $conncetionInstance;

    public function __construct($conncetionInstance)
    {
        $this->conncetionInstance = $conncetionInstance;
    }

    public function migrateStaffTables()
    {
        $statements = [
            'CREATE TABLE IF NOT EXISTS employees (
        employee_id   INTEGER PRIMARY KEY AUTOINCREMENT,
        first_name VARCHAR (255) NOT NULL,
        last_name VARCHAR (255) NOT NULL,
        middle_name VARCHAR (255) NOT NULL,
        start_date TEXT,
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
    }


    public function getTableList()
    {

        $stmt = $this->conncetionInstance->query("SELECT name
                                   FROM sqlite_master
                                   WHERE type = 'table'
                                   ORDER BY name");
        $tables = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $tables[] = $row['name'];
        }

        return $tables;
    }
}
