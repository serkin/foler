<?php

$app['controllers']['translation/save'] = function ($app, $request){

    
    parse_str($request['form'], $form);

    $idCode = !empty($form['id_code'])      ? (int)$form['id_code'] : null;
    $arr    = !empty($form['translation'])  ? $form['translation']  : [];
    
    $result = $app['foler']->saveTranslation($arr, $idCode);

    if($result):
        Response::responseWithSuccess(['response' => 'ok', 'message' => 'Translation saved']);
    else:
        Response::responseWithError($app['foler']->getError()[2]);
    endif;

};