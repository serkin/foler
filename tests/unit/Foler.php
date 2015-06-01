<?php


class FolerTest extends PHPUnit_Framework_TestCase
{
    
    public function setUp()
    {
        parent::setUp();
    }

    public function testDBConnection()
    {

        require dirname(dirname(__DIR__)) . '/classes/general/Foler.php';
        
        $foler = new Foler($GLOBALS['db_dsn'], $GLOBALS['db_user'], $GLOBALS['db_password']);
        $foler->saveProject($arr);
        $this->assertTrue(true);

    }
}
