<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$app = array();

$app['config'] = array(
    'db' => array(
        'dsn'      => 'mysql:dbname=foler;host=localhost',
        'user'      => 'root',
        'password'  => 'CFGHNBV'
    )
);
$app['locale'] = 'en';