<?php

namespace Seeders;

use Controllers\CurlController;
use Exception;

class StaffSeeder

{
    private $connectionInstance;

    public function __construct($conncetionInstance)
    {
        $this->connectionInstance = $conncetionInstance;
    }

    /**
     * Fetch staff list from remote api and store in database
     */
    public function migrateStaffData()
    {
        try {
            $curlController = new CurlController("https://api.npoint.io/82b00a6230d0e8de0b80");
            $stafflist[] = json_decode($curlController->makeCurlRequest());
            if (sizeof($stafflist) > 0) {
                foreach (json_decode($stafflist[0]) as $staff) {
                    $staffid = $this->saveStaff($staff);
                    $this->saveStaffDesignation($staffid, $staff);
                }
            }
        } catch (Exception $ex) {
            error_log($ex->getMessage());
        }
    }

    /**
     * Save staff data
     */
    private function saveStaff($staff)
    {
        try {
            $sql = "INSERT INTO employees(first_name,last_name,middle_name,start_date,is_at_company) 
            VALUES(:first_name,:last_name,:middle_name,:start_date,:is_at_company)";
            $createstaff = $this->connectionInstance->prepare($sql);
            if ($createstaff) {
                $createstaff->bindValue(':first_name', $staff->first_name);
                $createstaff->bindValue(':last_name', $staff->last_name);
                $createstaff->bindValue(':middle_name', $staff->middle_name);
                $createstaff->bindValue(':start_date', $staff->start_date);
                $createstaff->bindValue(':is_at_company', $staff->is_at_company);
                $createstaff->execute();
                return $this->connectionInstance->lastInsertId();
            } else {
                die("Failed to execute query" . print_r($this->connectionInstance->errorInfo(), true));
            }
        } catch (Exception $ex) {
            error_log($ex->getMessage());
        }
    }

    /**
     * Saves staff designation
     */
    private function  saveStaffDesignation($staffid, $staff)
    {
        try {
            $sql_1 = "INSERT INTO emp_designations(description,employee_id) 
            VALUES(:description,:employee_id)";
            $createstaffdesignation = $this->connectionInstance->prepare($sql_1);
            if ($createstaffdesignation) {
                $createstaffdesignation->bindValue(':description', $staff->designation);
                $createstaffdesignation->bindValue(':employee_id', $staffid);
                $createstaffdesignation->execute();
                return $this->connectionInstance->lastInsertId();
            } else {
                die("Failed to execute query" . print_r($this->connectionInstance->errorInfo(), true));
            }
        } catch (Exception $ex) {
            error_log($ex->getMessage());
        }
    }
}
