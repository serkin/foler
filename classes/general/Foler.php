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
    public function getAllCodes($idProject, $keyword = null)
    {

        $sth = $this->dbh->prepare('SELECT * FROM `code` WHERE `id_project` = ? and `code` like ?');

        $keyword = !is_null($keyword) ? "%$keyword%" : '';

        $sth->bindParam(1, $idProject, PDO::PARAM_INT);
        $sth->bindParam(2, $keyword, PDO::PARAM_STR);
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Gets translation according with given code
     * 
     * @param integer $idCode
     * @return array
     */
    public function getTranslation($idCode){

        $idProject = $this->getProjectIdByCodeId($idCode);
        
        $languages = $this->getLanguagesFromProject($idProject);

        ChromePhp::log($languages);
        $sth = $this->dbh->prepare('SELECT * FROM `translation` WHERE `id_code` = ?');
        $sth->bindParam(1, $idCode, PDO::PARAM_INT);

        $sth->execute();

        foreach($sth->fetchAll(PDO::FETCH_ASSOC) as $record):
            $result[$record['language']] = $record['translation'];
        endforeach;
        
        ChromePhp::log($result);

        $returnValue = [];
        $returnValue['id_code'] = $idCode;

        foreach ($languages as $lang):
            $returnValue['translations'][] = [
                'language'      => $lang,
                'translation'   => !empty($result[$lang]) ? $result[$lang] : ''
            ];
        endforeach;

        return $returnValue;      


    }

    /**
     * Get all information about project
     * 
     * @param integer $idProject
     * @return array
     */
    public function getProjectById($idProject)
    {
        $sth = $this->dbh->prepare("SELECT * FROM `project` WHERE `id_project` = ?");
        $sth->bindParam(1, $idProject, PDO::PARAM_INT);
        $sth->execute();
        return $sth->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getProjectIdByCodeId($idCode)
    {
        $sth = $this->dbh->prepare("SELECT `id_project` FROM `code` WHERE `id_code` = ?");
        $sth->bindParam(1, $idCode, PDO::PARAM_INT);
        $sth->execute();
        $arr = $sth->fetch(PDO::FETCH_ASSOC);
        
        return $arr['id_project'];
        
    }

    /**
     * Saves translation code
     * 
     * @param string $code
     * @param int $idProject
     * @return boolean
     */
    public function saveCode($code, $idProject)
    {

        $sth = $this->dbh->prepare('INSERT INTO `code` (`code`,`id_project`) VALUES(?, ?)');

        $sth->bindParam(1, $code, PDO::PARAM_STR);
        $sth->bindParam(2, $idProject, PDO::PARAM_INT);
        $sth->execute();

        return $this->dbh->lastInsertId() ? $this->dbh->lastInsertId() : false;
    }

    /**
     * Saves project
     * 
     * Edits project if $idProject was specified
     * 
     * @param array $arr
     * @param integer $idProject
     * @return int|boolean False on error
     */
    public function saveProject($arr, $idProject = null){

        $returnValue = false;

        if(is_null($idProject)):
            $sql = "INSERT INTO `project` (`name`, `path`, `languages`) VALUES('{$arr['name']}', '{$arr['path']}', '{$arr['languages']}')";
            $this->dbh->exec($sql) ? true : false;
            $returnValue = $this->dbh->lastInsertId() ? $this->dbh->lastInsertId() : false;

        else:
            
            // TODO bind params
            $sql = "UPDATE `project` SET `name` = '{$arr['name']}', `path` = '{$arr['path']}', `languages` = '{$arr['languages']}' WHERE `id_project` = " . $idProject;
            $returnValue = $this->dbh->exec($sql) ? $idProject : false;
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
     * @param array $arr
     * @param integer $idCode
     * @return boolean
     */
    public function saveTranslation($arr, $idCode)
    {
        

        $languages = $this->getLanguagesFromProject($this->getProjectIdByCodeId($idCode));

        foreach ($languages as $language):
            
            if(isset($arr[$language])):
                $value = !empty($arr[$language]) ? $arr[$language] : '';
            
                $sth = $this->dbh->prepare('INSERT INTO `translation` (`id_code`,`language`, `translation`) VALUES(?, ?, ?)');
                
                $sth->bindParam(1, $idCode, PDO::PARAM_INT);
                $sth->bindParam(2, $language, PDO::PARAM_STR);
                $sth->bindParam(3, $value, PDO::PARAM_STR);

                if($sth->execute() === false):
                    return false;
                endif;
            endif;
        endforeach;

        return true;
    }
    
    /**
     * 
     * @param integer $idCode
     * @return boolean
     */
    public function deleteCodeFromProject($idCode){}
    
    /**
     * 
     * @param integer $idProject
     * @param string $exportType
     * @return boolean
     */
    public function exportProject($idProject, $exportType){}
    
    private function getLanguagesFromProject($idProject){

        $returnValue = [];

        $data = $this->getProjectById($idProject);
        
        if(!empty($data['languages'])):
            $returnValue = explode(",", $data['languages']);
        endif;        

        return $returnValue;
    }
}