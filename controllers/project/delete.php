<?php



$app['controllers']['project/delete'] = function ($app, $request){


    $idProject = !empty($request['id_project']) ? (int)$request['id_project'] : null;    


    if(empty($idProject)):
        $result     = false;
        $errorMsg   = $app['i18n']['errors']['empty_id_project'];
    else:
        $result     = $app['foler']->deleteProject($idProject);
        $error      = $app['foler']->getError();
        $errorMsg   = $error[2];
    endif;


    if($result):
        Response::responseWithSuccess(array(), $app['i18n']['foler']['project_removed']);
    else:
        Response::responseWithError($errorMsg);
    endif;

};