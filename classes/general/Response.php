<?php

/**
 * Class creates two types of response according having error.
 *
 * @author Serkin Alexander <serkin.alexander@gmail.com>
 */
class Response {

    public static function sendResponse($response) {
        header('Content-Type: application/json');
        echo json_encode($response);
        die();
    }

    public static function responseWithError($message) {

        $response = array(
            'status' => array(
                'state' => 'notOk',
                'message' => $message,
            ),
            'data' => array(),
        );

        self::sendResponse($response);
    }

    public static function responseWithSuccess($arr, $statusMessage = '') {

        $response = array(
            'status' => array(
                'state' => 'Ok',
                'message' => $statusMessage,
            ),
            'data' => $arr,
        );

        self::sendResponse($response);
    }

}
