<?php
// Source: config/header.php


error_reporting(E_ALL);
ini_set('display_errors', 1);

$app = [];

$app['config'] = [
    'db' => [
        'dsn'      => 'mysql:dbname=foler;host=localhost',
        'user'      => 'root',
        'password'  => 'CFGHNBV'
    ]
];






// Source: classes/export/ExportInterface.php


// Content from ExportInterface file

// Source: classes/export/PHPArrayExport.php


// Content from PHPArrayExport file

// Source: classes/general/Foler.php



class Foler {
    
    protected $i18n = [];
    protected $dbh  = null;
    
    protected $dbDSN;
    protected $dbUser;
    protected $dbPassword;



    public function __construct($dbDSN, $dbUser, $dbPassword = '', $i18n = []) {
        
        $this->i18n         = $i18n;

        $this->dbDSN        = $dbDSN;
        $this->dbUser       = $dbUser;
        $this->dbPassword   = $dbPassword;

    }
    
    /**
     * Connects to database
     * 
     * @throws PDOException
     * @return void
     */
    public function connect(){
        $dbh = new PDO($this->dbDSN, $this->dbUser, $this->dbPassword);
        $this->dbh = $dbh;
    }

    

    public function getAllProjects(){

        $sth = $this->dbh->prepare("SELECT * FROM `project`");
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllMessages($idProject, $keyword = null){}
    
    public function getTranslation($idProject, $code){}
    
    public function getProjectData($idProject){}
    
    public function saveMessage($idProject, $code){}
    
    public function savePropject($arr, $idProject = null){}
    
    public function saveTranslation($idProject, $code, $arr){}
    
    
    public function deleteMessageFromProject($idProject, $code){}
    
    public function exportProject($idProject, $exportType){}
    
    
    
    
    
}

// Source: classes/general/Response.php


/**
 * Class creates two types of response according having error
 *
 * @author Serkin Alexander
 */
class Response {

    public static function responseWithError($message){

        header('Content-Type: application/json');

        $response = ['error' => $message];

        echo json_encode($response);
        die();
        
    }

    public static function responseWithSuccess($arr){

        header('Content-Type: application/json');

        $response = ['status' => 'ok'];
        $response['response'] = $arr;

        echo json_encode($response);
        die();
    }
}


// Source: i18n/en.php



$app['i18n']['en'] = [];

// Source: i18n/ru.php


$app['i18n']['ru'] = [];

// Source: controllers/project/getall.php




$app['controllers']['project/getall'] = function ($app, $post){
    
    $projects = $app['foler']->getAllProjects();
    Response::responseWithSuccess($projects);
    
};

// Source: config/footer.php



$app['foler'] = new Foler($app['config']['db']['dsn'], $app['config']['db']['user'], $app['config']['db']['password'], $app['i18n']);

try {
    $app['foler']->connect();
} catch (Exception $exc){
    Response::responseWithError($exc->getMessage());
}


if(!empty($_REQUEST['action']) && isset($app['controllers'][$_REQUEST['action']])):
    $app['controllers'][$_REQUEST['action']]($app, $_REQUEST);
endif;


?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Foler - Translation</title>

        <style>
            html {
                margin: 0;
                padding: 0;
            }
        </style>
    </head>
    <body onLoad="projects.reload()">
        
        <script>

function sendRequest(action, data, callback) {
    var request = new XMLHttpRequest();
    request.open('POST', 'foler.php?action='+action, true);
    request.onload = function() {
    if (request.status >= 200 && request.status < 400) {
      // Success!
      callback(JSON.parse(request.responseText));

    } else {
      // We reached our target server, but it returned an error

    }
  };
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    
    request.send(data);
}

var projects = {
    
    reload: function() {
        sendRequest('project/getall',{}, function(response){

            var out = '';

            for (var i in response.response) {
                out += response.response[i].name + "<br />";


                
            }

            var el = document.querySelectorAll('#projects');
                el[0].innerHTML = out;

            console.log(out);
        });
    }
};
</script>
        <table>
            <tr>
                <td colspan="2">
                    Foler
                </td>
                <td>
                    <div id="status">
                        Status
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div id="projects"></div>
                </td>
                <td>
                    table 2
                </td>
                <td>
                    table 3
                </td>
            </tr>
        </table>
        
    </body>
</html>