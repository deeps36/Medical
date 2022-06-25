<?php
namespace FES\GEET\ClassLib\ClassDBase;
use FES\GEET\ClassLib\Database;
use FES\GEET\ClassLib\ClassDBase\ApiDBase;

class ImportDbase extends Database{

	protected $database;
	protected $apiDBase;
	function __construct(){

		$this->database = new Database;
		$this->apiDBase = new ApiDBase;
	}

    public function exportToolLabel($tool_id){

		$query = "select lm.uid,lm.id,lm.labelname from label_master lm join tools_label_relation tlr on lm.id=tlr.label_id where tlr.tool_id = ? and lm.deleted is not true and tlr.deleted is not true order by lm.id";

		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1,intval($tool_id), \PDO::PARAM_INT);
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

	public function exportLabelTranslation($tool_id){

		$language = $this->apiDBase->getLanguages();
		$caseArray = array();
		foreach ($language['text'] as $lang) {
			$caseArray[] = "min(CASE WHEN lm2.id = '".$lang['id']."' THEN llr.translation end) as \"".$lang['name']." (".$lang['id'].")\"";
		}
		$query = "select lm.uid,lm.id,lm.labelname, ".implode(", ", $caseArray)." from label_master lm join tools_label_relation tlr on lm.id =tlr.label_id and tlr.tool_id=? left outer join label_language_relation llr on lm.id=llr.label_id left outer join language_master lm2 on lm2.id=llr.lang_id where llr.deleted is not true and lm.deleted is not true and tlr.deleted is not true group by lm.id,lm.labelname order by lm.id";

		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1,$tool_id, \PDO::PARAM_STR);
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

	function addLabel($labelname,$labelid){

		$query = " WITH upsert AS (UPDATE label_master SET labelname = ?, update_date = to_timestamp(".time()."),update_by = '".$_SESSION['userid']."' WHERE id= ? and deleted is not true RETURNING id) insert into label_master (labelname,create_date,create_by) select ?,to_timestamp(".time()."),'".$_SESSION['userid']."' where not exists (select * from label_master where id = ? and deleted is not true) and NOT EXISTS (SELECT * FROM upsert) returning id";

		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1,$labelname, \PDO::PARAM_STR);
		$statement->bindParam(2,$labelid, \PDO::PARAM_INT);
		$statement->bindParam(3,$labelname, \PDO::PARAM_STR);
		$statement->bindParam(4,$labelid, \PDO::PARAM_INT);
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

	function addToolLabel($tool_id,$label_id){

		$query = " Insert into tools_label_relation(tool_id,label_id,create_date, create_by) select ?,?,to_timestamp(".time()."),'".$_SESSION['userid']."' where not exists (select * from tools_label_relation where tool_id=? and label_id = ? and deleted is not true)";

		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1,intval($tool_id), \PDO::PARAM_INT);
		$statement->bindParam(2,intval($label_id), \PDO::PARAM_INT);
		$statement->bindParam(3,intval($tool_id), \PDO::PARAM_INT);
		$statement->bindParam(4, intval($label_id), \PDO::PARAM_INT);
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

	function deleteLabel($labelid){

		$query = "UPDATE label_master SET deleted = true,deleted_date = to_timestamp(".time()."),deleted_by = '".$_SESSION['userid']."' WHERE id = ? and deleted is not true";
		
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, intval($labelid), \PDO::PARAM_INT);
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

	function deleteToolLabel($tool_id,$labelid){

		$query = "UPDATE tools_label_relation SET deleted = true,deleted_date = to_timestamp(".time()."),deleted_by = '".$_SESSION['userid']."' WHERE tool_id = ? and label_id = ? and deleted is not true";
		
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, intval($tool_id), \PDO::PARAM_INT);
		$statement->bindParam(2, intval($labelid), \PDO::PARAM_INT);
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

	function addTranslation($label_id,$lang_id,$translation){
		
		$query = "WITH upsert AS (UPDATE label_language_relation SET translation = ?, update_date = to_timestamp(".time()."),update_by = '".$_SESSION['userid']."' WHERE label_id= ? and lang_id = ? and deleted is not true RETURNING *) insert into label_language_relation (label_id,lang_id,translation,create_date,create_by) select ?,?,?,to_timestamp(".time()."),'".$_SESSION['userid']."' where not exists (select * from label_language_relation where label_id = ? and lang_id = ? and deleted is not true) and NOT EXISTS (SELECT * FROM upsert) ";
		
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1,$translation, \PDO::PARAM_STR);
		$statement->bindParam(2,intval($label_id), \PDO::PARAM_INT);
		$statement->bindParam(3,intval($lang_id), \PDO::PARAM_INT);
		$statement->bindParam(4,intval($label_id), \PDO::PARAM_INT);
		$statement->bindParam(5,intval($lang_id), \PDO::PARAM_INT);
		$statement->bindParam(6,$translation, \PDO::PARAM_STR);
		$statement->bindParam(7,intval($label_id), \PDO::PARAM_INT);
		$statement->bindParam(8,intval($lang_id), \PDO::PARAM_INT);
		
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

	function deleteTranslation($label_id,$lid){

		$query = "UPDATE label_language_relation SET deleted = true,deleted_date = to_timestamp(".time()."),deleted_by = '".$_SESSION['userid']."' WHERE lang_id = ? and label_id = ? and deleted is not true";
		
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, intval($lid), \PDO::PARAM_INT);
		$statement->bindParam(2, intval($label_id), \PDO::PARAM_INT);
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