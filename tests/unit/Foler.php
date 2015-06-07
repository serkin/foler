<?php

require_once dirname(dirname(__DIR__)) . '/classes/general/Foler.php';



class Folers extends PHPUnit_Framework_TestCase
{

    /**
     * @var Foler
     */
    public $foler;

    /**
     * @var array
     */
    public $project;


    /**
     * @var PDOStatement
     */
    public $dbh;

    public function setUp()
    {


        parent::setUp();
        $this->foler = new Foler($GLOBALS['db_dsn'], $GLOBALS['db_user'], $GLOBALS['db_password']);
        $this->foler->connect();

        $this->dbh = new PDO($GLOBALS['db_dsn'], $GLOBALS['db_user'], $GLOBALS['db_password']);
        $this->dbh->exec('SET NAMES utf8');
        $this->truncateDB();

        $this->project = [
            'name' => 'project 1',
            'path' => 'path',
            'languages' => 'ru,en'
        ];

    }

    public function insertProject()
    {
        return $this->foler->saveProject($this->project['name'], $this->project['path'], $this->project['languages']);
    }


    public function truncateDB()
    {

        foreach($this->foler->getAllProjects() as $project) {
            $this->foler->deleteProject($project['id_project']);
        }
    }


    public function testInsertingProject()
    {

        $idProject = $this->foler->saveProject($this->project['name'], $this->project['path'], $this->project['languages']);
        $project = $this->foler->getProjectById($idProject);

        $this->assertTrue(is_numeric($idProject));
        $this->assertEquals($project['name'], $this->project['name']);
        $this->assertEquals($project['path'], $this->project['path']);
        $this->assertEquals($project['languages'], $this->project['languages']);

        // Updating project

        $idProject2 = $this->foler->saveProject($this->project['name'].'0', $this->project['path'], $this->project['languages'], $idProject);
        $project = $this->foler->getProjectById($idProject2);
        $this->assertEquals($project['name'], $this->project['name'].'0');




    }


    public function testGetAllProjects()
    {
        $this->insertProject();

        $projects = $this->foler->getAllProjects();
        $this->assertCount(1, $projects);
    }


    public function testGetAllLanguagesFromProjects()
    {
        $id = $this->insertProject();

        $arr = $this->foler->getLanguagesFromProject($id);
        $this->assertEquals(['ru','en'], $arr);
    }

    public function testSaveTranslation()
    {

        $arr = [
            'code' => 'button.save',
            'translations' => [
                'ru'    => 'Сохранить',
                'en'    => 'Save'
            ]
        ];


        $expected = [
            'code'          => $arr['code'],
            'translations'  => [
                ['language' => 'ru', 'translation' => 'Сохранить'],
                ['language' => 'en', 'translation' => 'Save']

            ]
        ];

        $idProject = $this->insertProject();

        $result = $this->foler->saveTranslation($idProject, $arr['code'], $arr['translations']);
        $this->assertTrue($result);

        $translation = $this->foler->getTranslation($idProject, $arr['code']);

        $this->assertEquals(json_encode($translation), json_encode($expected));


        $translation = $this->foler->getAllTranslationsFromProject($idProject);


        $expected = [
            [
            'id_project'    => $idProject,
            'code'          => $arr['code'],
            'language'      => 'en',
            'translation'   => 'Save'
            ],
            [
                'id_project'    => $idProject,
                'code'          => $arr['code'],
                'language'      => 'ru',
                'translation'   => 'Сохранить'
            ]
        ];

        $this->assertEquals(json_encode($translation), json_encode($expected));



    }

    public function testGetCodes()
    {

        $arr = [
        'code' => 'button.save',
        'translations' => [
            'ru'    => 'Сохранить',
            'en'    => 'Save'
        ]
    ];

        $arr2 = [
            'code' => 'button.remove',
            'translations' => [
                'ru'    => 'Удалить',
                'en'    => 'Remove'
            ]
        ];


        $idProject = $this->insertProject();

        $this->foler->saveTranslation($idProject, $arr['code'], $arr['translations']);
        $this->foler->saveTranslation($idProject, $arr2['code'], $arr2['translations']);


        $allCodes = $this->foler->getAllCodes($idProject);
        $this->assertCount(2, $allCodes);

        $customCodes = $this->foler->getAllCodes($idProject, $keyword = 'remove');
        $this->assertCount(1, $customCodes);
        $this->assertEquals($customCodes[0]['code'], $arr2['code']);


        $this->foler->deleteCode($idProject, $arr2['code']);

        $allCodes = $this->foler->getAllCodes($idProject);
        $this->assertCount(1, $allCodes);
        $this->assertEquals($allCodes[0]['code'], $arr['code']);

    }



}
