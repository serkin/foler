<?php



$app['controllers']['project/save'] = function ($app, $request){
    

    parse_str($request['form'], $data);
    $idProject = !empty($data['id_project']) ? $data['id_project'] : null;
    
    $result = $app['foler']->saveProject($data, $idProject);
    
    if($result):
        Response::responseWithSuccess(['response' => ['id_project' => $result], 'message' => 'Project saved']);
    else:
        Response::responseWithError($app['foler']->getError()[2]);
    endif;
    
};