<?php
namespace FES\GEET\ClassLib\ClassDBase;
use FES\GEET\ClassLib\Database;


class ContactDBase extends Database
{
	protected $database;

	function __construct()
	{
		$this->database = new Database;
	}

	function postFeedback($fname, $lname, $email, $subject, $message){
		$query = "insert into contactus_master (fname, lname, cemail, csubject, cmessage, ctimestamp) values (?,?,?,?,?, to_timestamp(".time()."))";
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $fname, \PDO::PARAM_STR);
		$statement->bindParam(2, $lname, \PDO::PARAM_STR);
		$statement->bindParam(3, $email, \PDO::PARAM_INT);
		$statement->bindParam(4, $subject, \PDO::PARAM_INT);
		$statement->bindParam(5, $message, \PDO::PARAM_INT);
		try{
			$statement->execute();
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$result = $statement->rowCount();
			if($result <= 0 ){
				$response['responseType'] = '2';
				$response['text'] = "No data available.";
			} else{
				$response['responseType'] = '1';
				$response['text'] = $result." row(s) affected.";
			}
			return $response;
		} catch(\PDOException $e) {
			error_log("Got sql error at line ".__LINE__." in ".__FILE__.". ".$e->getMessage());
			$response['responseType'] = '-1';
			$response['text'] = 'Error - query failed';
			$response['error'] = 'Error - query failed';
			return $response;
		} finally {
			$this->db = null;
		}
	}
}