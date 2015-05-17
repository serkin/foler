<?php



$app['controllers']['translation/getone'] = function ($app, $request){
    
    $code       = !empty($request['code'])          ? $request['code']              : null;
    $idProject  = !empty($request['id_project'])    ? (int)$request['id_project']   : null;


    $result = $app['foler']->getTranslation($idProject, $code);
    Response::responseWithSuccess($result);

};