<?php



$app['controllers']['code/save'] = function ($app, $request){
    


    $data = (array)json_decode($request['form']);
    
    $idProject  = !empty($data['id_project']) ? $data['id_project'] : null;
    $code       = !empty($data['code']) ? $data['code'] : null;
    
    
    $result = $app['foler']->saveCode($code, $idProject);
    
    
    if($result):
        Response::responseWithSuccess(['response' => ['id_code' => $result], 'message' => 'Code added']);
    else:
        Response::responseWithError($app['foler']->getError()[2]);
    endif;
    
};