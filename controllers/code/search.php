<?php



$app['controllers']['code/search'] = function ($app, $request){

    $keyword    = !empty($request['keyword'])       ? $request['keyword']           : null;
    $idProject  = !empty($request['id_project'])    ? (int)$request['id_project']   : null;

    $codes = $app['foler']->getAllCodes($idProject, $keyword);
    Response::responseWithSuccess(array('codes' => $codes));

};