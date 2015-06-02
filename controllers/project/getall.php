<?php

$app['controllers']['project/getall'] = function ($app) {

    $projects = $app['foler']->getAllProjects();
    Response::responseWithSuccess(array('projects' => $projects));

};
