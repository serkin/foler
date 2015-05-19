<?php

$isCodeValid = function($code) {

    return preg_match('/^[a-z0-9_\.]+$/', $code) === 1 ? true : false;
    
};

$app['controllers']['translation/save'] = function ($app, $request) use($isCodeValid) {

    parse_str(urldecode($request['form']), $form);


    $idProject  = !empty($form['id_project'])   ? $form['id_project']   : null;
    $code       = !empty($form['code'])         ? $form['code']         : null;
    $arr        = !empty($form['translation'])  ? $form['translation']  : array();

    if(empty($idProject)):
        $result     = false;
        $errorMsg   = $app['i18n']['errors']['empty_id_project'];
    elseif(empty($code) or $isCodeValid($code) === false):
        $result     = false;
        $errorMsg   = $app['i18n']['errors']['not_valid_project_code'];
    else:
        $result     = $app['foler']->saveTranslation($idProject, $code, $arr);
        $error      = $app['foler']->getError();
        $errorMsg   = $error[2];
    endif;

    if($result):
        Response::responseWithSuccess(array(), $app['i18n']['foler']['translation_saved']);
    else:
        Response::responseWithError($errorMsg);
    endif;

};