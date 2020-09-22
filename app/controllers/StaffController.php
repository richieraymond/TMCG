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
            ,Q.last_name,Q.middle_name,Q.start_date,Q.created_on,Q.employee_id,
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
                    $employeeId = $row['employee_id'];
                    if ($staff_actions != null) {

                        $employeeActions = array_filter((json_decode($staff_actions[0])), function ($action) use ($employeeId) {
                            return $action->employee_id == $employeeId;
                        });

                        if (sizeof($employeeActions) > 0) {

                            if (sizeof(($employeeActions >= sizeof(json_decode($staff_actions[0]))))) {
                                $userRole = "Admin";
                            }else{
                                $userRole=null;
                            }

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
                                    }
                                }
                            }
                        } else {
                            $actionDescriptions = [];
                        }
                    }

                    if ($userRole == null && $editorCount == 2) {
                        $userRole = "Editor";
                    }
                    if ($userRole == null && $viewerCount >= 1) {
                        $userRole = "Viewer";
                    }

                    $employeeList[] = [
                        'employee_id' => $row['employee_id'],
                        'name' => $row['first_name'] . ' ' . $row['last_name'] . ' ' . $row['middle_name'],
                        'start_date' => $row['start_date'],
                        'designation' => $row['description'],
                        'created_on' => $row['created_on'],
                        'actions' => $actionDescriptions,
                        'userRole' => $userRole
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
}
