<?php

require 'vendor/autoload.php';
/**
 * Handle all app routing from here
 * Allow cross origin requests
 */
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

$appRouter = new AltoRouter();
$dotenv->load();

$appRouter->map('POST', '/', 'Controllers\InitController#index', 'init-app');
$appRouter->map('GET', '/get-staff', 'Controllers\StaffController#getAll', 'get-staff');
$appRouter->map('GET', '/get-similar-staff', 'Controllers\StaffController#getSimilarStaff', 'get-similar-staff');

$match = $appRouter->match();

list($controller, $action) = explode('#', $match['target']);
if (is_callable(array($controller, $action))) {
    $obj = new $controller();
    call_user_func_array(array($obj, $action), array($match['params']));
} else if ($match['target'] == '') {
    echo 'Error: no route was matched';
    error_log("No routes matched");
} else {
    echo 'Error: can not call controller';
    error_log("Unable to call controller");
}
