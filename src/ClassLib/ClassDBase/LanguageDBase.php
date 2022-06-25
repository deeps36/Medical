<?php
namespace FES\GEET\ClassLib\ClassDBase;
use FES\GEET\ClassLib\Database;

class LanguageDbase extends Database{

	protected $database;
	
	function __construct(){

		$this->database = new Database;
	}

    function getAllLanguages($id = null){
    	$where = '';
    	if($id !== null){
    		$where = ' id = ? and ';
    	}
        $query="select * from language_master where $where deleted is not true order by name";
        $this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		if($id !== null){
			$statement->bindParam(1, intval($id), \PDO::PARAM_INT);
		}
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

	public function addLanguage($lang,$lang_desc,$lang_code){
		
		$query = "INSERT INTO language_master(name,lang_desc,language_code,create_date,created_by) VALUES(?,?,?,to_timestamp(".time()."),'".$_SESSION['userid']."')";
		
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $lang, \PDO::PARAM_STR);
		$statement->bindParam(2, $lang_desc, \PDO::PARAM_STR);
		$statement->bindParam(3, $lang_code, \PDO::PARAM_STR);
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

	public function postUpdateLanguage($lang,$lang_desc,$lang_code,$id){

		$query = "UPDATE language_master SET name = ?,lang_desc = ?, language_code = ?, update_date = to_timestamp(".time()."),updated_by = '".$_SESSION['userid']."' WHERE id = ?";

		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1,$lang, \PDO::PARAM_STR);
		$statement->bindParam(2,$lang_desc, \PDO::PARAM_STR);
		$statement->bindParam(3,$lang_code, \PDO::PARAM_STR);
		$statement->bindParam(4, intval($id), \PDO::PARAM_INT);
		
			
		try{
			$statement->execute();
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$result = $statement->rowCount();
			if($result <= 0){
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
	
	public function deleteLanguage($id){

		$query = "UPDATE language_master SET deleted = true, deleted_date = to_timestamp(".time()."), deleted_by = '".$_SESSION['userid']."' WHERE id = ?";

		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, intval($id), \PDO::PARAM_INT);
		try{
			$statement->execute();
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$result = $statement->rowCount();
			if($result <= 0){
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
?>