<?php

$joinStringToArr = function($string, $value, &$arr = []) {

    $keys = explode('.', $string);

    $ref = &$arr;

    while ($key = array_shift($keys)) {
        $ref = &$ref[$key];
    }

    $ref = $value;

};

$app['controllers']['project/export'] = function($app, $request) use ($joinStringToArr) {

    $idProject  = !empty($request['id_project']) ? (int)$request['id_project'] : null;
    $type       = (!empty($request['type']) && in_array($request['type'], array('php', 'yaml'))) ? $request['type'] : 'php';

    $project    = $app['foler']->getProjectById($idProject);
    $languages  = $app['foler']->getLanguagesFromProject($idProject);

    $result = true;
    $directory = $project['path'];

    if (empty($project['path']) || !is_writable($directory)) {
        $result     = false;
        $errorMsg   = $app['i18n']['errors']['project_path_not_writable'] . ': ' . $directory;

    } else {

        switch ($type) {
            case 'php':
                $export = new PHPExport();
                break;

            case 'yaml':
                $export = new YAMLExport();
                break;
        }

        $records = $app['foler']->getAllTranslationsFromProject($idProject);

        foreach ($languages as $language):
            $out = array();

            foreach ($records as $record):
                if ($record['language'] == $language):
                    $joinStringToArr($record['code'], $record['translation'], $out);
                endif;
            endforeach;

            if ($export->export($out, $directory, $language) === false):
                $result     = false;
                $errorMsg   = $app['i18n']['errors']['cannot_export_project'] . ': ' . $language;
            endif;

        endforeach;

}

    if ($result === true) {
        Response::responseWithSuccess(array(), $app['i18n']['foler']['project_exported']);
    } else {
        Response::responseWithError($errorMsg);
    }

};
