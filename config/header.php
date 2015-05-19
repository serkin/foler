<?php

$app = array();

$app['config'] = array(
    'db' => array(
        'dsn'      => 'mysql:dbname=foler;host=localhost',
        'user'      => 'foler',
        'password'  => ''
    ),
    'url' => $_SERVER['PHP_SELF'],
    'debug' => false
);



if($app['config']['debug']):
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
endif;


$app['locale'] = 'en';


/**
 * Helps us split codes to associative array
 * 
 * @param type $string
 * @param type $value
 * @param array $arr
 */
function joinStringToArr($string, $value, &$arr = array()) {
    
        $keys = explode('.', $string);

        $ref = &$arr;

        while ($key = array_shift($keys)) {
            $ref = &$ref[$key];
        }

        $ref = $value;

    }