<?php



$app['controllers']['translation/getone'] = function ($app, $request){
    
    $idCode = !empty($request['id_code']) ? (int)$request['id_code'] : null;

    if(!is_null($idCode)):
        $result = $app['foler']->getTranslation($idCode);
        Response::responseWithSuccess($result);
    else:
        Response::responseWithError('Code id not correct');
    endif;

};