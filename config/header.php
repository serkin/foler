<?php

$app = [];

$app['config'] = [
    'db' => [
        'dsn'       => 'mysql:dbname=foler;host=localhost',
        'user'      => 'foler',
        'password'  => ''
    ],
    'url' => $_SERVER['PHP_SELF'],
    'debug' => false
];



if($app['config']['debug']):
    error_reporting(E_ALL);
endif;


$app['locale'] = 'en';

