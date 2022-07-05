<?php
namespace FES\GEET\ClassLib\ClassDBase;
use FES\GEET\ClassLib\Database;

/**
* Scheme class
*/
class UtilsDBase extends Database
{
	protected $database;

	function __construct()
	{
		$this->database = new Database();
	}
	
	
	// function getLanguageList(){
	// 	$query = "select * from language_master";
	// 	$this->db= $this->database->coreConnection();
	// 	$statement = $this->db->prepare($query);
	// 	try{
	// 		$statement->execute();
	// 		$statement->setFetchMode(\PDO::FETCH_ASSOC);
	// 		$result = $statement->fetchAll();
	// 		if(sizeof($result) <= 0){
	// 			$response['responseType'] = '2';
	// 			$response['text'] = "No data available.";
	// 		} else{
	// 			$response['responseType'] = '1';
	// 			$response['text'] = $result;
	// 		}
	// 		return $response;
	// 	} catch(\PDOException $e) {
	// 		error_log("Got sql error at line ".__LINE__." in ".__FILE__.". ".$e->getMessage());
	// 		$response['responseType'] = '-1';
	// 		$response['text'] = 'Error - query failed';
	// 		$response['error'] = 'Error - query failed';
	// 		return $response;
	// 	} finally {
	// 		$this->db = null;
	// 	}

	// 	//$response = $this->database->coreConnection($query);
	// 	//return $response;
	// }
	
	function validateGETPOST($data){
		return $this->database->validateGETPOST($data);
	}

	function getStatesCount(){
    	$query = "select count(distinct state_code) as count from schemes_master";
    	$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		try{
			$statement->execute();
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$result = $statement->fetchAll();
			if(sizeof($result) <= 0){
				$response['responseType'] = '2';
				$response['text'] = "No data available.";
			} else{
				$response['responseType'] = '1';
				$response['text'] = $result;
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

    function getSchemesCount(){
    	$query = "select count(sc_id) as count from schemes_master";
    	$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		try{
			$statement->execute();
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$result = $statement->fetchAll();
			if(sizeof($result) <= 0){
				$response['responseType'] = '2';
				$response['text'] = "No data available.";
			} else{
				$response['responseType'] = '1';
				$response['text'] = $result;
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

    function getIndHHCount(){
    	$query = "select count(ec_id) as eccount, count(distinct hh_id) as hhcount from ind_master where org_id is not null";
    	$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		try{
			$statement->execute();
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$result = $statement->fetchAll();
			if(sizeof($result) <= 0){
				$response['responseType'] = '2';
				$response['text'] = "No data available.";
			} else{
				$response['responseType'] = '1';
				$response['text'] = $result;
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

    function getPartnersCount(){
    	$query = "select count(distinct org_id) as count from organization_user_relation";
    	$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		try{
			$statement->execute();
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$result = $statement->fetchAll();
			if(sizeof($result) <= 0){
				$response['responseType'] = '2';
				$response['text'] = "No data available.";
			} else{
				$response['responseType'] = '1';
				$response['text'] = $result;
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

    function getPartners(){
    	$query = "select * from organization_master where id in (select distinct org_id from organization_user_relation)";
    	$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		try{
			$statement->execute();
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$result = $statement->fetchAll();
			if(sizeof($result) <= 0){
				$response['responseType'] = '2';
				$response['text'] = "No data available.";
			} else{
				$response['responseType'] = '1';
				$response['text'] = $result;
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