<?php



$app['controllers']['project/delete'] = function ($app, $request){
    
    
    // Checks if id_oriject exists
    
    
    // Save project
    
    // Edit project
    $data = (array)json_decode($request['form']);
    

    $idProject = !empty($data['id_project']) ? (int)$data['id_project'] : null;

    
    
    $result = $app['foler']->deleteProject($idProject);
    
    
    if($result):
        Response::responseWithSuccess(['response' => [], 'message' => 'Project deleted']);
    else:
        Response::responseWithError($app['foler']->getError()[2]);
    endif;
    
};