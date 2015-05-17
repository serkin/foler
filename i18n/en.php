<?php


$app['i18n']['en'] = array(
    'layout' => array(
        'code_placeholder'  => 'code',
        'save'              => 'Save',
        'code'              => 'Code',
        'title'             => 'Foler - single page translation system',
        'name'              => 'Name',
        'manage'            => 'Manage',
        'languages'         => 'Languages',
        'path'              => 'Path for export',
        'clear'             => 'Clear',
        'new_translation'   => 'Add new translation',
        'add_project'       => 'Add/edit project',
        'export'       => 'Export',
        'delete'            => 'delete'
        ),
    'foler' => array(
        'project_saved' => 'Project saved!',
        'project_removed' => 'Project removed!',
        'translation_saved' => 'Translation saved!',
        'code_removed' => 'Code removed!',
        'project_exported' => 'Project exported!',
    ),
    'errors' => array(
        'empty_code' => 'Code not specified',
        'cannot_export_project' => 'Cannot export  project',
        'project_path_not_writable' => 'Cannot write to project export path',
        'empty_id_project' => 'ID project not specified',
        'empty_project_name' => 'Project name not specified',
        'empty_project_export_path' => 'Project export path not specified',
        'not_valid_project_languages' => 'Languages field should be unique two letters string separated by comma',
        'not_valid_project_code' => 'Code field should consists of only dots, numbers and letters in loser case'
    )
);