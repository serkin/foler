<?php

class Foler {

    /**
     *
     * @var array
     */
    protected $i18n = [];
    
    /**
     *
     * @var PDO 
     */
    protected $dbh  = null;

    /**
     *
     * @var string
     */
    protected $dbDSN;
    
    /**
     *
     * @var string
     */
    protected $dbUser;
    
    /**
     *
     * @var string
     */
    protected $dbPassword;

    /**
     *
     * @var string
     */
    protected $error;

    /**
     * 
     * @param string $dbDSN
     * @param string $dbUser
     * @param string $dbPassword
     * @param array $i18n
     */
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
    
    /**
     * Sets error
     * 
     * @param string $error
     * @return void
     */
    public function setError($error)
    {
        $this->error($error);
    }

    /**
     * Clears last error
     * 
     * @return void
     */
    private function clearError() {
        $this->error = null;
    }

    public function getError()
    {
        return $this->dbh->errorInfo();
    }
    
    public function hasError()
    {
        $this->dbh->errorInfo();
    }

    /**
     * Gets all projects
     * 
     * @return array
     */
    public function getAllProjects(){

        $sth = $this->dbh->prepare("SELECT * FROM `project`");
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Gets all codes from project
     * 
     * @param integer $idProject
     * @param string $keyword
     * @return array
     */
    public function getAllCodes($idProject, $keyword = null){}

    /**
     * Gets translation according with given code
     * 
     * @param integer $idProject
     * @param string $code
     * @return array
     */
    public function getTranslation($idProject, $code){}

    /**
     * Get all information about project
     * 
     * @param integer $idProject
     * @return array
     */
    public function getProjectData($idProject){}

    /**
     * Saves translation code
     * 
     * @param integer $idProject
     * @param string $code
     * @return boolean
     */
    public function saveCode($idProject, $code){}

    /**
     * Saves project
     * 
     * Edits project if $idProject was specified
     * 
     * @param array $arr
     * @param integer $idProject
     * @return int|boolean False on error
     */
    public function savePropject($arr, $idProject = null){

        $returnValue = false;

        if(is_null($idProject)):
            $sql = "INSERT INTO `project` (`name`, `path`, `languages`) VALUES('{$arr['name']}', '{$arr['path']}', '{$arr['languages']}')";
            $this->dbh->exec($sql) ? true : false;
            $returnValue = $this->dbh->lastInsertId() ? $this->dbh->lastInsertId() : false;

        else:
            $sql = "UPDATE `project` SET `name` = '{$arr['name']}', `path` = '{$arr['path']}', `languages` = '{$arr['languages']}' WHERE `id` = " . $idProject;
            $returnValue = $this->dbh->exec($sql) ? true : false;
        endif;

        return $returnValue;

    }
    
    /**
     * Removes project
     * 
     * @param int $idProject
     * @return boolean
     */
    public function deleteProject($idProject){
        
        $sql = "DELETE FROM `project` WHERE `id_project` = " . $idProject;
        return $this->dbh->exec($sql) ? true : false;
    }


    /**
     * 
     * @param integer $idProject
     * @param string $code
     * @param array $arr
     * @return boolean
     */
    public function saveTranslation($idProject, $code, $arr){}
    
    /**
     * 
     * @param integer $idProject
     * @param string $code
     * @return boolean
     */
    public function deleteCodeFromProject($idProject, $code){}
    
    /**
     * 
     * @param integer $idProject
     * @param string $exportType
     * @return boolean
     */
    public function exportProject($idProject, $exportType){}

}