<?php

namespace Controllers;

use Config\InitDatabase;
use Exception;

class BaseController
{
    /**
     * send response method.
     *
     * @return Response
     */
    public static function sendResponse($status = true, $message, $code = 200, $extras = [])
    {
        header('Content-Type: application/json');
        http_response_code($code);

        $response = [
            'success' => $status,
            'message' => $message,
            'code' => $code
        ];

        if (!empty($extras)) {
            $response = array_merge($response, $extras);
        }

        return json_encode($response);
    }

    /**
     * Make database connection available to all controllers
     */
    protected function getConnection()
    {
        try {
            $connection = (new InitDatabase())->establishCOnnection();
            if ($connection != null) {
                return $connection;
            } else {
                echo "Connection Failed";
            }
        } catch (Exception $ex) {
            echo $this->sendResponse(
                false,
                'Failed to init db',
                400
            );
        }
    }
}
