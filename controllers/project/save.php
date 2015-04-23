<?php



$app['controllers']['project/save'] = function ($app, $request){
    
    
    // Checks if id_oriject exists
    
    
    // Save project
    
    // Edit project
    $data = (array)json_decode($request['form']);
    
    ChromePhp::log($data);
    $idProject = !empty($data['id_project']) ? $data['id_project'] : null;

    
    
    $result = $app['foler']->savePropject($data, $idProject);
    
    
    if($result):
        Response::responseWithSuccess(['response' => $data, 'message' => 'Project saved']);
    else:
        Response::responseWithError($app['foler']->getError()[2]);
    endif;
    
};