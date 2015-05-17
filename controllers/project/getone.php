<?php



$app['controllers']['project/getone'] = function ($app, $request){
    
    $idProject = !empty($request['id_project']) ? (int)$request['id_project'] : null;

    if(!is_null($idProject)):
        $project = $app['foler']->getProjectByID($idProject);
        Response::responseWithSuccess(['project' => $project]);
    else:
        Response::responseWithError($app['i18n']['errors']['empty_id_project']);
    endif;

};