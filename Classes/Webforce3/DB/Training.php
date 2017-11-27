<?php

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;
use Classes\Webforce3\Exceptions\InvalidSqlQueryException;

class Training extends DbObject{
    /** @var string */
    protected $name;

    public function __construct($id = 0,$name='', $inserted = '')
    {
        $this->name = $name;
        parent::__construct($id, $inserted);
    }

    /**
     * @param int $id
     * @return bool|Training
     * @throws InvalidSqlQueryException
     */
    public static function get($id)
    {
        $sql = '
            SELECT tra_id, tra_name, tra_inserted
            FROM training
            WHERE  tra_id = :id
        ';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        if ($stmt->execute() === false){
            throw new InvalidSqlQueryException($sql, $stmt);
        }
        else{
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!empty($row)){
                $currentObject = new Training(
                    $row['tra_id'],
                    $row['tra_name'],
                    $row['tra_inserted']
                );
                return $currentObject;
            }
        }
        return false;
    }

    /**
     * @return DbObject[]
     * @throws InvalidSqlQueryException
     */
    public static function getAll()
    {
        $returnList = array();

        $sql = '
		    SELECT tra_id, tra_name, tra_inserted
		    FROM training
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        if ($stmt->execute() === false){
            throw new InvalidSqlQueryException($sql, $stmt);
        }
        else{
            $allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($allDatas as $row){
                $currentObject = new Training(
                    $row['tra_id'],
                    $row['tra_name'],
                    $row['tra_inserted']
                );
                $returnList[] = $currentObject;
            }
        }
        return $returnList;
    }

    /**
     * @return array
     */
    public static function getAllForSelect()
    {
        $returnList = array();

        $sql = '
			SELECT tra_id, tra_name
			FROM training
			ORDER BY tra_name ASC
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        if ($stmt->execute() === false) {
            print_r($stmt->errorInfo());
        }
        else {
            $allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($allDatas as $row) {
                $returnList[$row['tra_id']] = $row['tra_name'];
            }
        }

        return $returnList;
    }

    /**
     * @return bool
     * @throws InvalidSqlQueryException
     */
    public function saveDB()
    {
        if ($this->id > 0){
            $sql = '
                UPDATE training
                SET tra_name = :tra_name,
                WHERE tra_id = :id
            ';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
            $stmt->bindValue(':tra_name', $this->name, \PDO::PARAM_STR);

            if ($stmt->execute() === false){
                throw new InvalidSqlQueryException($sql, $stmt);
            } else {
                return true;
            }
        } else {
            $sql = '
                INSERT INTO training (tra_name)
                VALUES (:tra_name)
            ';
            //var_dump($this->country->id);
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':tra_name', $this->name, \PDO::PARAM_STR);
            if ($stmt->execute() === false){
                throw new InvalidSqlQueryException($sql, $stmt);
            } else {
                return true;
            }
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws InvalidSqlQueryException
     */
    public static function deleteById($id)
    {
        $sql = '
			DELETE FROM training WHERE tra_id = :id
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        if ($stmt->execute() === false) {
            print_r($stmt->errorInfo());
        }
        else {
            return true;
        }
        return false;
    }


}