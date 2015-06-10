<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that frontpage works');
$I->amOnPage('/foler.php');
$I->see('Foler ');


//////////////////////////////
//      Add project         //
//////////////////////////////

$I->amOnPage('/foler.php');
$I->fillField('#projectInputName','Project 1');
$I->fillField('#projectInputLanguages','en,ru');
$I->fillField('#projectInputPath','.');
$I->wait(3);
$I->click('#projectButtonSave');
$I->see('Project saved');
$I->wait(3);
//////////////////////////////
//      Saving Translation  //
//////////////////////////////

$I->fillField('#codeInputLanguageen','Hello');
$I->fillField('#codeInputLanguageru','Привет');
$I->fillField('#codeInputCode','welcome');
$I->wait(3);
$I->click('#codeButtonSave');
$I->see('Translation saved');
$I->wait(3);
$idProject = $I->grabValueFrom('#idGlobalProject');
$I->click('#project_block_' . $idProject);
$I->wait(3);


////////////////////////////////
//      Search code           //
////////////////////////////////

$I->fillField('#searchKeyword','wel');
$I->see('welcome');
$I->wait(3);

////////////////////////////////
//      Select code           //
////////////////////////////////

$I->click('#codeButtonSelect_welcome');

////////////////////////////////
//      Updating Translation  //
////////////////////////////////

$I->fillField('#codeInputLanguageen','Hello my love');
$I->wait(3);
$I->click('#codeButtonSave');
$I->see('Translation saved');
$I->wait(3);
////////////////////////////////
//      Remove code           //
////////////////////////////////


//////////////////////////////////
//      Update project          //
//////////////////////////////////


//////////////////////////////////
//      Export project          //
//////////////////////////////////

//////////////////////////////////
//      Remove project          //
//////////////////////////////////

$I->click('#projectButtonDelete_' . $idProject);
$I->see('Project removed');
