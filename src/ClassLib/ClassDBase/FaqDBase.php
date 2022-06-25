<?php
namespace FES\GEET\ClassLib\ClassDBase;
use FES\GEET\ClassLib\Database;


class FaqDBase extends Database
{
	protected $database;

	function __construct()
	{
		$this->database = new Database;
	}
	
	function getFaqs(){
		$query = "select * from faq_master where end_date is null or end_date > '".date('Y-m-d')."'::date order by weight ASC";
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

	function getFaqUpdate($id){
		$query = "select * from faq_master where id = ? and (end_date is null or end_date >= '".date('Y-m-d')."'::date) order by weight ASC";
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, intval($id), \PDO::PARAM_INT);
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

	function postUpdateFaq($id, $question, $answer, $weight){
		$query = "update faq_master set question = ?, answer = ?, weight = ?, update_date = to_timestamp(".time()."), update_by = '".$_SESSION['userid']."' where id = ?";
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $question, \PDO::PARAM_STR);
		$statement->bindParam(2, $answer, \PDO::PARAM_STR);
		$statement->bindParam(3, intval($weight), \PDO::PARAM_INT);
		$statement->bindParam(4, intval($id), \PDO::PARAM_INT);
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

	function deleteFaq($id){
		$query = "update faq_master set end_date = '".date('Y-m-d')."'::date where id = ?";
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, intval($id), \PDO::PARAM_INT);
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

	function postNewFaq($question, $answer, $weight){
		$query = "insert into faq_master (question, answer, weight, create_date, create_by) values (?,?,?, to_timestamp(".time()."), '".$_SESSION['userid']."')";
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $question, \PDO::PARAM_STR);
		$statement->bindParam(2, $answer, \PDO::PARAM_STR);
		$statement->bindParam(3, intval($weight), \PDO::PARAM_INT);
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