<?php

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;
use Classes\Webforce3\Exceptions\InvalidSqlQueryException;

class Location extends DbObject{
    /** @var string */
    protected $name;
    /** @var Country */
    protected $country;

    public function __construct($id = 0, $name = '', $country = null,  $inserted = '')
    {
        $this->country = empty($country) ? new Country() : $country;
        $this->name = $name;
        parent::__construct($id, $inserted);
    }

    /**
     * @param int $id
     * @return bool|City
     * @throws InvalidSqlQueryException
     */
    public static function get($id)
    {
        $sql = '
            SELECT loc_id, loc_name, loc_inserted, country_cou_id
            FROM location
            WHERE  loc_id = :id
        ';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        if ($stmt->execute() === false){
            throw new InvalidSqlQueryException($sql, $stmt);
        }
        else{
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!empty($row)){
                $currentObject = new City(
                    $row['loc_id'],
                    $row['loc_name'],
                    new Country($row['country_cou_id']),
                    $row['loc_inserted']
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
		    SELECT loc_id, loc_name, loc_inserted, country_cou_id
		    FROM location
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        if ($stmt->execute() === false){
            throw new InvalidSqlQueryException($sql, $stmt);
        }
        else{
            $allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($allDatas as $row){
                $currentObject = new City(
                    $row['loc_id'],
                    $row['loc_name'],
                    new Country($row['coutry_cou_id']),
                    $row['loc_inserted']
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
			SELECT loc_id,loc_name
			FROM location
			ORDER BY loc_name ASC
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        if ($stmt->execute() === false) {
            print_r($stmt->errorInfo());
        }
        else {
            $allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($allDatas as $row) {
                $returnList[$row['loc_id']] = $row['loc_name'];
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
                UPDATE location
                SET loc_name = :loc_name,
                country_cou_id = :cou_id
                WHERE loc_id = :id
            ';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
            $stmt->bindValue(':loc_name', $this->name, \PDO::PARAM_STR);
            $stmt->bindValue(':cou_id', $this->country->id, \PDO::PARAM_INT);

            if ($stmt->execute() === false){
                throw new InvalidSqlQueryException($sql, $stmt);
            } else {
                return true;
            }
        } else {
            $sql = '
                INSERT INTO location (loc_name, country_cou_id)
                VALUES (:loc_name, :cou_id)
            ';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':loc_name', $this->name, \PDO::PARAM_STR);
            $stmt->bindValue(':cou_id', $this->country->id, \PDO::PARAM_INT);
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
			DELETE FROM location WHERE loc_id = :id
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

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Country
     */
    public function getCountry(): Country
    {
        return $this->country;
    }



}