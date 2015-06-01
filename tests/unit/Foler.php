<?php


class FolerTest extends PHPUnit_Framework_TestCase
{
    
    public function setUp()
    {
        parent::setUp();
    }

    public function testInsertingProject()
    {

        require dirname(dirname(__DIR__)) . '/classes/general/Foler.php';
        
        $arr = [
            'name'      => 'project 1',
            'path'      => 'path',
            'languages' => 'ru,en'
        ];
        
        $foler = new Foler($GLOBALS['db_dsn'], $GLOBALS['db_user'], $GLOBALS['db_password']);
        $foler->connect();
        $id = $foler->saveProject($arr['name'], $arr['path'], $arr['languages']);
        $project = $foler->getProjectById($id);

        $this->assertTrue(is_numeric($id));
        $this->assertEquals($project['name'], $arr['name']);
        $this->assertEquals($project['path'], $arr['path']);
        $this->assertEquals($project['languages'], $arr['languages']);
        
        
        // Test update project

    }
}
