<?php


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