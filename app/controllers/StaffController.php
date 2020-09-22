<?php

namespace Controllers;

use Config\Constants;
use Exception;

use function PHPSTORM_META\type;

/**
 * Handle staff quries here
 */
class StaffController extends BaseController
{
    private $connectionInstance;

    public function __construct()
    {
        $this->connectionInstance = $this->getConnection();
    }

    /**
     * Get All Staff/ Employees With the Required Data
     * Read staff actions directly from csv and read employee actions directly from api
     */
    public function getAll()
    {
        try {

            $stmt = $this->connectionInstance->query('SELECT Q.first_name
            ,Q.last_name,Q.middle_name,Q.start_date,Q.created_on,Q.employee_id,Q.is_at_company,
            D.description from employees Q join emp_designations D USING(employee_id)');

            $curl_instance = (new CurlController("https://api.npoint.io/6f81bbc4b547399e70ea"));
            $staff_actions[] = json_decode($curl_instance->makeCurlRequest());
            $read_csv = (new ReadCSVController(Constants::CSV_LOCATION));
            $actions = json_decode($read_csv->readCSV());

            if ($stmt) {
                $employeeList = [];
                while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    $employeeActions = [];
                    $actionDescriptions = [];
                    $editorCount = 0;
                    $viewerCount = 0;
                    $loginCounter = 0;
                    $employeeId = $row['employee_id'];
                    if ($staff_actions != null) {

                        $employeeActions = array_filter((json_decode($staff_actions[0])), function ($action) use ($employeeId) {
                            return $action->employee_id == $employeeId;
                        });

                        if (sizeof($employeeActions) > 0) {

                            foreach ($employeeActions as $empaction) {
                                $actionid = $empaction->employee_action;

                                $actiondescription = array_filter($actions, function ($description) use ($actionid) {
                                    return $description[0] == $actionid;
                                });

                                if (sizeof($actiondescription) > 0) {
                                    foreach ($actiondescription as $desc) {
                                        $actionDescriptions[] = array($desc[1]);
                                        if ($desc[1] == 'Can view posts' || $desc[1] == ' Can Edit Post') {
                                            $editorCount += 1;
                                        }
                                        if ($desc[1] == 'Can view posts' || $desc[1] == ' Can View Comments') {
                                            $viewerCount += 1;
                                        }
                                        if ($desc[1] == 'can Login') {
                                            $loginCounter += 1;
                                        }
                                    }
                                }
                            }
                        } else {
                            $actionDescriptions = [];
                        }
                    }

                    if (sizeof($employeeActions) >= sizeof($actions) - 1) {
                        $userRole = "Admin";
                    } else if (sizeof($employeeActions) < 1) {
                        $userRole = "(Not Set)";
                    } else {
                        $userRole = "";
                    }

                    if ($userRole == "" && $editorCount == 2) {
                        $userRole = "Editor";
                    }
                    if ($userRole == "" && $viewerCount >= 1) {
                        $userRole = "Viewer";
                    }
                    if ($userRole == "") {
                        $userRole = "(Undefined)";
                    }

                    if ($row['start_date'] != "" || $row['start_date'] != null) {
                        $diff = date_diff(date_create($row['start_date']), date_create(date("m/d/Y")));
                        $interval = date_create($row['start_date'])->diff(date_create(date("m/d/Y")));
                        $dateDiff = $interval->format("%r%a");
                        if ($loginCounter >= 1 && $row['is_at_company']) {
                            $staffStatus = "Active";
                        } else if (!$row['is_at_company'] && $dateDiff <= 0) {
                            $staffStatus = "Pending";
                        } else if (!$row['is_at_company'] && $dateDiff >= 0) {
                            $staffStatus = "Left";
                        }
                    } else {
                        $staffStatus = "Undefined";
                    }

                    if ($dateDiff / 365 >= 2) {
                        $timeSpentStatus = "Old";
                    } else {
                        $timeSpentStatus = "New";
                    }

                    $employeeList[] = [
                        'employee_id' => $row['employee_id'],
                        'name' => $row['first_name'] . ' ' . $row['last_name'] . ' ' . $row['middle_name'],
                        'start_date' => $row['start_date'],
                        'designation' => $row['description'],
                        'created_on' => $row['created_on'],
                        'user_role' => $userRole,
                        'staff_status' => $staffStatus,
                        'time_spent_status' => $timeSpentStatus,
                        'actions' => $actionDescriptions,
                    ];
                }

                echo $this->sendResponse(
                    true,
                    "Staff Retrieved Successfully",
                    200,
                    ['employeelist' => $employeeList]
                );
            } else {
                die("Failed to execute query" . print_r($this->connectionInstance->errorInfo(), true));
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }


    public function getSimilarStaff()
    {
        try {
            $name =  htmlspecialchars($_GET["name"]);
            $stmt = $this->connectionInstance->prepare("SELECT (Q.first_name||' '||Q.last_name||' '||Q.middle_name) as name, Q.start_date,Q.created_on,Q.employee_id,Q.is_at_company,
            D.description from employees Q join emp_designations D USING(employee_id) WHERE name like :userName;");

            if ($stmt) {
                $stmt->execute([':userName' => '%' .$name. '%']);
            } else {
                die("Failed to execute query" . print_r($this->connectionInstance->errorInfo(), true));
            }

            if ($stmt) {
                $employeeList = [];
                while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    $employeeList[] = [
                        'employee_id' => $row['employee_id'],
                        'name' => $row['name'],
                        'start_date' => $row['start_date'],
                        'designation' => $row['description'],
                        'created_on' => $row['created_on'],
                    ];
                }
                echo $this->sendResponse(
                    true,
                    "Staff Retrieved Successfully",
                    200,
                    ['employeelist' => $employeeList,'employeename'=>$name]
                );
            } else {
                die("Failed to execute query" . print_r($this->connectionInstance->errorInfo(), true));
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
