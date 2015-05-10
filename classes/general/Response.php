<?php

/**
 * Class creates two types of response according having error
 *
 * @author Serkin Alexander
 */
class Response {

    public static function responseWithError($message){

        header('Content-Type: application/json');

        $response = [
            'status' => [
                'state'     => 'notOk',
                'message'   => $message
                ],
            'data'  => []
                ];

        echo json_encode($response);
        die();

    }

    public static function responseWithSuccess($arr, $statusMessage = ''){

        header('Content-Type: application/json');

        $response = [
            'status' => [
                'state'     => 'Ok',
                'message'   => $statusMessage
                ],
            'data'  => $arr
                ];

        echo json_encode($response);
        die();
    }
}