<?php

$isLanguagesValid =  function($languages) {
    
    $returnValue = true;

    if(strpos($languages, ' ') !== false):
        $returnValue = false;
    endif;
    
    $uniqueArr = array();
    
    foreach (explode(',', $languages) as $value):
        if(empty($value) or strlen($value) != 2 or isset($uniqueArr[$value])):
            $returnValue = false;
        endif;
        $uniqueArr[$value] = 1;
    endforeach;
    
    return $returnValue;

};

$app['controllers']['project/save'] = function ($app, $request) use ($isLanguagesValid){

    parse_str($request['form'], $form);

    $idProject = !empty($form['id_project']) ? $form['id_project'] : null;

    
    if(empty($form['languages']) or $isLanguagesValid($form['languages']) === false):
        $result     = false;
        $errorMsg   = $app['i18n']['errors']['not_valid_project_languages'];
    elseif(empty($form['path'])):
        $result     = false;
        $errorMsg   = $app['i18n']['errors']['empty_project_export_path'];
    elseif(empty($form['name'])):
        $result     = false;
        $errorMsg   = $app['i18n']['errors']['empty_project_name'];
    else:
        $result     = $app['foler']->saveProject($form, $idProject);
        $error      = $app['foler']->getError();
        $errorMsg   = $error[2];
    endif;

    if($result):
        Response::responseWithSuccess(array('id_project' => $result), $app['i18n']['foler']['project_saved']);
    else:
        Response::responseWithError($errorMsg);
    endif;
    
};