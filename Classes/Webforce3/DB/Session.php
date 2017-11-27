<?php

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;
use Classes\Webforce3\DB\Location;
use Classes\Webforce3\DB\Training;
use Classes\Webforce3\Exceptions\InvalidSqlQueryException;

class Session extends DbObject {
    /** @var integer */
    protected $number;
    /** @var string */
    protected $start;
    /** @var string */
    protected $end;
    /** @var Location */
    protected $location;
    /** @var Training */
    protected $training;

    public function __construct($id = 0, $number=0, $start='', $end='',$location=null, $training=null,  $inserted = '')
    {
        $this->number = $number;
        $this->start = $start;
        $this->end = $end;
        if (empty($location)){
            $this->location = new Location();
        }else{
            $this->location = $location;
        }
        if (empty($training)){
            $this->training = new Country();
        }else{
            $this->training = $training;
        }
        parent::__construct($id, $inserted);
    }

    /**
	 * @param int $id
	 * @return DbObject|bool
     * @throws InvalidSqlQueryException
     */
	public static function get($id) {
        $sql = '
            SELECT ses_id, ses_number, ses_start_date, ses_end_date, ses_inserted, location_loc_id, training_tra_id
            FROM session
            WHERE  ses_id = :id
        ';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        if ($stmt->execute() === false){
            throw new InvalidSqlQueryException($sql, $stmt);
        }
        else{
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!empty($row)){
                $currentObject = new Session(
                    $row['ses_id'],
                    $row['ses_number'],
                    $row['ses_start_date'],
                    $row['ses_end_date'],
                    new Location($row['location_loc_id']),
                    new Training($row['training_tra_id']),
                    $row['ses_inserted']
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
		    SELECT ses_id, ses_number, ses_start_date, ses_end_date, ses_inserted, location_loc_id, training_tra_id
            FROM "session"
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        if ($stmt->execute() === false){
            throw new InvalidSqlQueryException($sql, $stmt);
        }
        else{
            $allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($allDatas as $row){
                $currentObject = new Session(
                    $row['ses_id'],
                    $row['ses_number'],
                    $row['ses_start_date'],
                    $row['ses_start_end'],
                    new Location($row['location_loc_id']),
                    new Training($row['training_tra_id']),
                    $row['ses_inserted']
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
			SELECT ses_id, tra_name, ses_start_date, ses_end_date, loc_name
			FROM session
			LEFT OUTER JOIN training ON training.tra_id = session.training_tra_id
			LEFT OUTER JOIN location ON location.loc_id = session.location_loc_id
			WHERE ses_id > 0
			ORDER BY ses_start_date ASC
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		if ($stmt->execute() === false) {
			print_r($stmt->errorInfo());
		}
		else {
			$allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			foreach ($allDatas as $row) {
				$returnList[$row['ses_id']] = '['.$row['ses_start_date'].' > '.$row['ses_end_date'].'] '.$row['tra_name'].' - '.$row['loc_name'];
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
                UPDATE session
                SET ses_number = :snumber,
                ses_start_date = :sstart,
                ses_end_date = :send,
                location_loc_id = :locId,
                training_tra_id = :traId
                WHERE ses_id = :id
            ';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
            $stmt->bindValue(':snumber', $this->number, \PDO::PARAM_INT);
            $stmt->bindValue(':sstart', $this->start, \PDO::PARAM_STR);
            $stmt->bindValue(':send', $this->end, \PDO::PARAM_STR);
            $stmt->bindValue(':locId', $this->location->getId(), \PDO::PARAM_INT);
            $stmt->bindValue(':traId', $this->training->getId(), \PDO::PARAM_INT);

            if ($stmt->execute() === false){
                throw new InvalidSqlQueryException($sql, $stmt);
            } else {
                return true;
            }
        } else {
            $sql = '
                INSERT INTO session (ses_start_date, ses_end_date, ses_number, location_loc_id, training_tra_id)
                VALUES (:sstart, :send, :snumber, :locId, :traId)
            ';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':snumber', $this->number, \PDO::PARAM_INT);
            $stmt->bindValue(':sstart', $this->start, \PDO::PARAM_STR);
            $stmt->bindValue(':send', $this->end, \PDO::PARAM_STR);
            $stmt->bindValue(':locId', $this->location->getId(), \PDO::PARAM_INT);
            $stmt->bindValue(':traId', $this->training->getId(), \PDO::PARAM_INT);
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
	 */
	public static function deleteById($id) {
        $sql = '
			DELETE FROM session WHERE ses_id = :id
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
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getStart(): string
    {
        return $this->start;
    }

    /**
     * @return string
     */
    public function getEnd(): string
    {
        return $this->end;
    }

    /**
     * @return Location
     */
    public function getLocation(){
        return $this->location;
    }

    /**
     * @return Training
     */
    public function getTraining(){
        return $this->training;
    }



}