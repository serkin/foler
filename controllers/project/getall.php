<?php

$app['controllers']['project/getall'] = function ($app, $request){
    
    $projects = $app['foler']->getAllProjects();
    Response::responseWithSuccess(array('projects' => $projects));
    
};