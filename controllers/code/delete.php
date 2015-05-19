<?php

$app['controllers']['code/delete'] = function ($app, $request){

    $idProject  = !empty($request['id_project'])    ? (int)$request['id_project']   : null;
    $code       = !empty($request['code'])          ? $request['code']              : null;


    if(empty($idProject)):
        $result     = false;
        $errorMsg   = $app['i18n']['errors']['empty_id_project'];
    elseif(empty($code)):
        $result     = false;
        $errorMsg   = $app['i18n']['errors']['empty_code'];
    else:
        $result = $app['foler']->deleteCode($idProject, $code);
        $error      = $app['foler']->getError();
        $errorMsg   = $error[2];
    endif;


    if($result):
        Response::responseWithSuccess(array(), $app['i18n']['foler']['code_removed']);
    else:
        Response::responseWithError($errorMsg);
    endif;
    
};