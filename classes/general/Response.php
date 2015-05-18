<?php

/**
 * Class creates two types of response according having error
 * @author Serkin Alexander <serkin.alexander@gmail.com>
 */
class Response {

    public static function responseWithError($message){

        header('Content-Type: application/json');

        $response = array(
            'status' => array(
                'state'     => 'notOk',
                'message'   => $message
                ),
            'data'  => array()
                );

        echo json_encode($response);
        die();

    }

    public static function responseWithSuccess($arr, $statusMessage = ''){

        header('Content-Type: application/json');

        $response = array(
            'status' => array(
                'state'     => 'Ok',
                'message'   => $statusMessage
                ),
            'data'  => $arr
                );

        echo json_encode($response);
        die();
    }
}