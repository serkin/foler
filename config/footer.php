<?php


$app['foler'] = new Foler($app['config']['db']['dsn'], $app['config']['db']['user'], $app['config']['db']['password'], $app['i18n']);

try {
    $app['foler']->connect();
} catch (Exception $exc){
    Response::responseWithError($exc->getMessage());
}


if(!empty($_REQUEST['action']) && isset($app['controllers'][$_REQUEST['action']])):
    $app['controllers'][$_REQUEST['action']]($app, $_REQUEST);
endif;

