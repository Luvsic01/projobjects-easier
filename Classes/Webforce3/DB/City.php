<?php

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;
use Classes\Webforce3\Exceptions\InvalidSqlQueryException;

class City extends DbObject {
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
	public static function get($id) {
        $sql = '
            SELECT cit_id, cit_name, cit_inserted, country_cou_id
            FROM city
            WHERE  cit_id = :id
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
                    $row['cit_id'],
                    $row['cit_name'],
                    new Country($row['country_cou_id']),
                    $row['cit_inserted']
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
	public static function getAll() {
		$returnList = array();

		$sql = '
		    SELECT cit_id, cit_name, cit_inserted, country_cou_id
		    FROM city
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		if ($stmt->execute() === false){
		    throw new InvalidSqlQueryException($sql, $stmt);
        }
	    else{
		    $allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		    foreach ($allDatas as $row){
		        $currentObject = new City(
                    $row['cit_id'],
                    $row['cit_name'],
                    new Country($row['coutry_cou_id']),
                    $row['cit_inserted']
                );
		        $returnList[] = $currentObject;
            }
        }
	    return $returnList;
	}

	/**
	 * @return array
	 */
	public static function getAllForSelect() {
		$returnList = array();

		$sql = '
			SELECT cit_id, cit_name
			FROM city
			WHERE cit_id > 0
			ORDER BY cit_name ASC
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		if ($stmt->execute() === false) {
			print_r($stmt->errorInfo());
		}
		else {
			$allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			foreach ($allDatas as $row) {
				$returnList[$row['cit_id']] = $row['cit_name'];
			}
		}

		return $returnList;
	}

	/**
	 * @return bool
     * @throws InvalidSqlQueryException
	 */
	public function saveDB() {
        if ($this->id > 0){
            $sql = '
                UPDATE city
                SET cit_name = :cit_name,
                country_cou_id = :cou_id
                WHERE cit_id = :id
            ';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
            $stmt->bindValue(':cit_name', $this->name, \PDO::PARAM_STR);
            $stmt->bindValue(':cou_id', $this->country->id, \PDO::PARAM_INT);

            if ($stmt->execute() === false){
                throw new InvalidSqlQueryException($sql, $stmt);
            } else {
                return true;
            }
        } else {
            $sql = '
                INSERT INTO city (cit_name, country_cou_id)
                VALUES (:cit_name, :cou_id)
            ';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':cit_name', $this->name, \PDO::PARAM_STR);
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
	public static function deleteById($id) {
        $sql = '
			DELETE FROM city WHERE cit_id = :id
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
     * @return Country
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }



}