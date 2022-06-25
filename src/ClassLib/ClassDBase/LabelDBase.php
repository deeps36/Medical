<?php
namespace FES\GEET\ClassLib\ClassDBase;
use FES\GEET\ClassLib\Database;

class LabelDbase extends Database{

	protected $database;
	
	function __construct(){

		$this->database = new Database;
	}

    function getAllLabels($id = null){
    	$where = '';
    	if($id !== null){
    		$where = ' id = ? and ';
    	}
        $query = "select id,uid,labelname, label_desc, create_date from label_master where $where deleted is not true order by labelname";

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

    function addLabel($label,$label_desc){

        $query = "INSERT INTO label_master(labelname,label_desc,create_date,create_by) VALUES (?,?,to_timestamp(".time()."),'".$_SESSION['userid']."') returning id";
		
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $label, \PDO::PARAM_STR);
		$statement->bindParam(2, $label_desc, \PDO::PARAM_STR);
		
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

	function addLabelLanguage($label_id,$lang_id,$translation){

		$query = " INSERT INTO label_language_relation(label_id,lang_id,translation,create_date,create_by) values( ?,?,?,to_timestamp(".time()."),'".$_SESSION['userid']."') returning lang_id";

		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, intval($label_id), \PDO::PARAM_INT);
		$statement->bindParam(2, intval($lang_id), \PDO::PARAM_INT);
		$statement->bindParam(3, $translation, \PDO::PARAM_STR);
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

	public function postUpdateLabel($id,$labelname,$labeldesc){

		$query = "UPDATE label_master SET labelname = ?,label_desc = ?, update_date = to_timestamp(".time()."),update_by = '".$_SESSION['userid']."' WHERE id = ? ";

		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1,$labelname, \PDO::PARAM_STR);
		$statement->bindParam(2,$labeldesc, \PDO::PARAM_STR);
		$statement->bindParam(3, intval($id), \PDO::PARAM_INT);
		
			
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

	public function getLanguages(){

		$query = "select * from language_master where deleted is not true order by name";

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

	public function updateLabelLanguage($label_id,$lang_id,$translation){
		
		$query = "WITH upsert AS (UPDATE label_language_relation SET translation = ?, update_date = to_timestamp(".time()."),update_by = '".$_SESSION['userid']."' WHERE label_id= ? and lang_id = ? and deleted is not true RETURNING *) insert into label_language_relation (label_id,lang_id,translation,create_date,create_by) select ?,?,?,to_timestamp(".time()."),'".$_SESSION['userid']."' where not exists (select * from label_language_relation where label_id = ? and lang_id = ? and translation = ? and deleted is not true) and NOT EXISTS (SELECT * FROM upsert) ";
		
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
		$statement->bindParam(9,$translation, \PDO::PARAM_STR);
			
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

	public function deleteLabel($id){

		$query = "UPDATE label_master SET deleted = true, deleted_date = to_timestamp(".time()."),deleted_by = '".$_SESSION['userid']."' WHERE id = ?";

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

	function getLangByLabel($label_id){

		$query = "SELECT lm.uid,llr.lang_id,llr.translation from label_language_relation llr join label_master lm	on llr.label_id = lm.id where lm.id = ? and llr.deleted is not true";

		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, intval($label_id), \PDO::PARAM_INT);
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

	function deleteLabelLanguage($label_id,$lang_id){
		$notin = array();
		foreach ($lang_id as $key => $value) {
			if(!empty($value)){
				$notin[] = '?';
			}
		}
		if(sizeof($notin) < 1){
			foreach ($lang_id as $key => $value) {
				$notin[] = '?';
			}
			$notinString = implode(',', $notin);
			$where = " in ($notinString) ";
			$notin = array();
		} else{
			$notinString = implode(',', $notin);
			$where = " not in ($notinString) ";
		}
		$query = "UPDATE label_language_relation SET deleted = true,deleted_date = to_timestamp(".time()."),deleted_by = '".$_SESSION['userid']."' WHERE lang_id $where and label_id = ? and deleted is not true";
		
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$i = 1;
		if(sizeof($notin) < 1){
			foreach ($lang_id as $key => $value) {
				$statement->bindParam($i, intval($key), \PDO::PARAM_INT);
				$i++;
			}
		}else{
			foreach ($lang_id as $key => $value) {
				if(!empty($value)){
					$statement->bindParam($i, intval($key), \PDO::PARAM_INT);
					$i++;
				}	
			}
		}
		$statement->bindParam($i, intval($label_id), \PDO::PARAM_INT);
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