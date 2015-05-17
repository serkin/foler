<?php



$app['controllers']['project/delete'] = function ($app, $request){


    $idProject = !empty($request['id_project']) ? (int)$request['id_project'] : null;    


    if(empty($idProject)):
        $result     = false;
        $errorMsg   = $app['i18n']['errors']['empty_id_project'];
    else:
        $result     = $app['foler']->deleteProject($idProject);
        $errorMsg   = $app['foler']->getError()[2];
    endif;


    if($result):
        Response::responseWithSuccess([], $app['i18n']['foler']['project_removed']);
    else:
        Response::responseWithError($errorMsg);
    endif;

};