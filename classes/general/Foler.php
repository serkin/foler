<?php

/**
 * @author Serkin Alexander <serkin.alexander@gmail.com>
 */
class Foler
{
    /**
     * @var PDO
     */
    private $dbh = null;

    /**
     * @var string
     */
    private $dbDSN;

    /**
     * @var string
     */
    private $dbUser;

    /**
     * @var string
     */
    private $error;

    /**
     * @var string
     */
    private $dbPassword;


    /**
     * @param string $dbDSN
     * @param string $dbUser
     * @param string $dbPassword
     */
    public function __construct($dbDSN, $dbUser, $dbPassword = '')
    {
        $this->dbDSN        = $dbDSN;
        $this->dbUser       = $dbUser;
        $this->dbPassword   = $dbPassword;
    }

    public function hasError()
    {
        return !empty($this->error);
    }

    public function getError()
    {
        return $this->error;
    }

    public function clearError()
    {
        $this->error = null;
    }

    /**
     * @param string $error
     */
    public function setError($error) {
        $this->error = $error;
    }

    /**
     * Connects to database.
     *
     * @throws PDOException
     */
    public function connect()
    {
        $dbh = new PDO($this->dbDSN, $this->dbUser, $this->dbPassword);
        $this->dbh = $dbh;
        $this->dbh->exec('SET NAMES utf8');

    }

    /**
     * Gets all projects.
     *
     * @return array
     */
    public function getAllProjects()
    {
        $sth = $this->dbh->prepare('SELECT * FROM `project`');
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Gets all codes from project.
     *
     * @param int    $idProject
     * @param string $keyword
     *
     * @return array
     */
    public function getAllCodes($idProject, $keyword = null)
    {
        $sth = $this->dbh->prepare('SELECT DISTINCT (`code`) FROM `translation` WHERE `id_project` = ? and `code` like ?');

        $keyword = !is_null($keyword) ? "%$keyword%" : '%%';

        $sth->bindParam(1, $idProject, PDO::PARAM_INT);
        $sth->bindParam(2, $keyword, PDO::PARAM_STR);

        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Gets translation according with given code and idProject or all records.
     *
     * @param int    $idProject
     * @param string $code
     *
     * @return array
     */
    public function getTranslation($idProject, $code = null)
    {
        $languages = $this->getLanguagesFromProject($idProject);

        $dbRecords = !is_null($code) ? $this->getCodeTranslation($idProject, $code) : array();

        $returnValue = array();
        $returnValue['code'] = $code;

        foreach ($languages as $lang):
            $returnValue['translations'][] = [
                'language'      => $lang,
                'translation'   => !empty($dbRecords[$lang]) ? $dbRecords[$lang] : '',
            ];
        endforeach;

        return $returnValue;
    }

    public function getAllTranslationsFromProject($idProject)
    {
        $sth = $this->dbh->prepare('SELECT * FROM `translation` WHERE `id_project` = ?');
        $sth->bindParam(1, $idProject, PDO::PARAM_INT);
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getCodeTranslation($idProject, $code)
    {
        $returnValue = array();

        $sth = $this->dbh->prepare('SELECT * FROM `translation` WHERE `id_project` = ? and `code` = ?');
        $sth->bindParam(1, $idProject, PDO::PARAM_INT);
        $sth->bindParam(2, $code, PDO::PARAM_STR);

        $sth->execute();

        foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $record):
            $returnValue[$record['language']] = $record['translation'];
        endforeach;

        return $returnValue;
    }

    /**
     * Get all information about project.
     *
     * @param int $idProject
     *
     * @return array
     */
    public function getProjectById($idProject)
    {
        $sth = $this->dbh->prepare('SELECT * FROM `project` WHERE `id_project` = ?');
        $sth->bindParam(1, $idProject, PDO::PARAM_INT);
        $sth->execute();

        return $sth->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Saves project.
     *
     * Edits project if $idProject was specified
     *
     * @param string $name
     * @param string $path
     * @param string $languages
     * @param int    $idProject
     *
     * @return int|bool False on error
     */
    public function saveProject($name, $path, $languages, $idProject = null)
    {

        $this->clearError();

        if (is_null($idProject)) {
            $sth = $this->dbh->prepare('INSERT INTO `project` (`name`, `path`, `languages`) VALUES(?, ?, ?)');
        } else {
            $sth = $this->dbh->prepare('UPDATE `project` SET `name` = ?, `path` = ?, `languages` = ? WHERE `id_project` = ?');
        }

        $sth->bindParam(1, $name, PDO::PARAM_STR);
        $sth->bindParam(2, $path, PDO::PARAM_STR);
        $sth->bindParam(3, $languages, PDO::PARAM_STR);

        if (is_null($idProject)) {
            $sth->execute();
            $returnValue = $this->dbh->lastInsertId() ? $this->dbh->lastInsertId() : 0;
        } else {
            $sth->bindParam(4, $idProject, PDO::PARAM_INT);
            $sth->execute();
            $returnValue = $idProject;
        }


        if(!empty($sth->errorInfo()[2])) {
            $this->setError($sth->errorInfo()[2]);
        }

        return $returnValue;
    }

    /**
     * Removes project.
     *
     * @param int $idProject
     *
     * @return bool
     */
    public function deleteProject($idProject)
    {
        $sth = $this->dbh->prepare('DELETE FROM `project` WHERE `id_project` = ?');

        $sth->bindParam(1, $idProject, PDO::PARAM_INT);

        return $sth->execute();
    }

    /**
     * @param int    $idProject
     * @param string $code
     * @param array  $arr
     *
     * @return bool
     */
    public function saveTranslation($idProject, $code, $arr)
    {
        $languages = $this->getLanguagesFromProject($idProject);

        foreach ($languages as $language) {

            if (isset($arr[$language])) {
                $value = !empty($arr[$language]) ? $arr[$language] : '';

                $sth = $this->dbh->prepare('INSERT INTO `translation` (`id_project`, `code`, `language`, `translation`) VALUES(?, ?, ?, ?)'
                                .'ON DUPLICATE KEY UPDATE `translation` = ?');

                $sth->bindParam(1, $idProject, PDO::PARAM_INT);
                $sth->bindParam(2, $code, PDO::PARAM_STR);
                $sth->bindParam(3, $language, PDO::PARAM_STR);
                $sth->bindParam(4, $value, PDO::PARAM_STR);
                $sth->bindParam(5, $value, PDO::PARAM_STR);

                if ($sth->execute() === false) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param int    $idProject
     * @param string $code
     *
     * @return bool
     */
    public function deleteCode($idProject, $code)
    {
        $sth = $this->dbh->prepare('DELETE FROM `translation` WHERE `code` = ? and `id_project` = ?');

        $sth->bindParam(1, $code, PDO::PARAM_STR);
        $sth->bindParam(2, $idProject, PDO::PARAM_INT);

        return $sth->execute();
    }

    public function getLanguagesFromProject($idProject)
    {
        $returnValue = array();

        $data = $this->getProjectById($idProject);

        if (!empty($data['languages'])):
            $returnValue = explode(',', $data['languages']);
        endif;

        return $returnValue;
    }
}
