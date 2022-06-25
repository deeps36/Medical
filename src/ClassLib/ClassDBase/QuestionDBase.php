<?php
namespace FES\GEET\ClassLib\ClassDBase;
use FES\GEET\ClassLib\Database;
use FES\GEET\ClassLib\ClassDBase\ApiDBase;

class QuestionDbase extends Database{

	protected $database;
	protected $apiDBase;
	function __construct(){

		$this->database = new Database;
		$this->apiDBase = new ApiDBase;
	}

	function getQuestions(){
		
		$query = "SELECT qm.que_id,qm.que_uid,qm.question,qm.create_date,qm.update_date,fm.name,fm.id,fm.uid,qm.value FROM question_master qm join form_master fm on qm.form_uid = fm.uid";

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

	function getForms(){
		
		$query = "SELECT fm.name,fm.id,fm.uid,fm.create_date FROM  form_master fm ";

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

	function getFormTranslation($form_uid = null){
		$where = '';
    	if($form_uid !== null){
    		$where = 'uid = ?';
    	}
		$query = "SELECT name,id,uid FROM form_master where $where  order by id";

		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		if($form_uid !== null){
			$statement->bindParam(1, $form_uid, \PDO::PARAM_STR);
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

	function getQuestionTraslation($que_uid = null){
		$where = '';
    	if($que_uid !== null){
    		$where = 'qm.que_uid = ?';
    	}
		$query = "SELECT qm.que_id,qm.que_uid,qm.question,qm.create_date,fm.name,fm.id,fm.uid,qm.value FROM question_master qm join form_master fm on qm.form_uid = fm.uid where $where order by qm.que_id";

		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		if($que_uid !== null){
			$statement->bindParam(1, $que_uid, \PDO::PARAM_STR);
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

	function addQuestionLanguage($que_uid,$lang_id,$translation){
		
		$query = "WITH upsert AS (UPDATE question_language_relation SET translation = ?, update_date = to_timestamp(".time()."),update_by = '".$_SESSION['userid']."' WHERE que_uid = ? and lang_id=?  and deleted is not true RETURNING *) insert into question_language_relation (que_uid,lang_id,translation,create_date,create_by) select ?,?,?,to_timestamp(".time()."),'".$_SESSION['userid']."' where not exists (select * from question_language_relation where que_uid = ? and lang_id = ? and deleted is not true) and NOT EXISTS (SELECT * FROM upsert) ";
		
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1,$translation, \PDO::PARAM_STR);
		$statement->bindParam(2,$que_uid, \PDO::PARAM_STR);
		$statement->bindParam(3,intval($lang_id), \PDO::PARAM_INT);
		$statement->bindParam(4,$que_uid, \PDO::PARAM_STR);
		$statement->bindParam(5,intval($lang_id), \PDO::PARAM_INT);
		$statement->bindParam(6,$translation, \PDO::PARAM_STR);
		$statement->bindParam(7,$que_uid, \PDO::PARAM_STR);
		$statement->bindParam(8,intval($lang_id), \PDO::PARAM_INT);
			
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

	function addFormLanguage($form_uid,$lang_id,$translation){

		$query = "WITH upsert AS (UPDATE form_language_relation SET translation = ?, update_date = to_timestamp(".time()."),update_by = '".$_SESSION['userid']."' WHERE form_uid = ? and lang_id=?  and deleted is not true RETURNING *) insert into form_language_relation (form_uid,lang_id,translation,create_date,create_by) select ?,?,?,to_timestamp(".time()."),'".$_SESSION['userid']."' where not exists (select * from form_language_relation where form_uid = ? and lang_id = ? and translation = ? and deleted is not true) and NOT EXISTS (SELECT * FROM upsert) ";
		
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1,$translation, \PDO::PARAM_STR);
		$statement->bindParam(2,$form_uid, \PDO::PARAM_STR);
		$statement->bindParam(3,intval($lang_id), \PDO::PARAM_INT);
		$statement->bindParam(4,$form_uid, \PDO::PARAM_STR);
		$statement->bindParam(5,intval($lang_id), \PDO::PARAM_INT);
		$statement->bindParam(6,$translation, \PDO::PARAM_STR);
		$statement->bindParam(7,$form_uid, \PDO::PARAM_STR);
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

	function addOptionLanguage($option_uid,$lang_id,$translation){

		$query = "WITH upsert AS (UPDATE option_language_relation SET translation = ?, update_date = to_timestamp(".time()."),update_by = '".$_SESSION['userid']."' WHERE option_uid = ? and lang_id=?  and deleted is not true RETURNING *) insert into option_language_relation (option_uid,lang_id,translation,create_date,create_by) select ?,?,?,to_timestamp(".time()."),'".$_SESSION['userid']."' where not exists (select * from option_language_relation where option_uid = ? and lang_id = ? and translation = ? and deleted is not true) and NOT EXISTS (SELECT * FROM upsert) ";
		
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1,$translation, \PDO::PARAM_STR);
		$statement->bindParam(2,$option_uid, \PDO::PARAM_STR);
		$statement->bindParam(3,intval($lang_id), \PDO::PARAM_INT);
		$statement->bindParam(4,$option_uid, \PDO::PARAM_STR);
		$statement->bindParam(5,intval($lang_id), \PDO::PARAM_INT);
		$statement->bindParam(6,$translation, \PDO::PARAM_STR);
		$statement->bindParam(7,$option_uid, \PDO::PARAM_STR);
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

	function getLangByQue($que_uid){

		$query = "SELECT qlr.lang_id,qlr.translation from question_language_relation qlr join question_master qm on qlr.que_uid = qm.que_uid where qm.que_uid = ? and qlr.deleted is not true";

		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $que_uid, \PDO::PARAM_STR);
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
	function getLangByForm($form_uid){

		$query = "SELECT flr.lang_id,flr.translation from form_language_relation flr join form_master fm on flr.form_uid = fm.uid where fm.uid = ? and flr.deleted is not true";

		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $form_uid, \PDO::PARAM_STR);
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

	public function exportQuestionTranslation($tool_id){

		$language = $this->apiDBase->getLanguages();
		$queCaseArray = array();
		$formCaseArray = array();
		foreach ($language['text'] as $lang) {
			$queCaseArray[] = "min(CASE WHEN lm.id = '".$lang['id']."' THEN qlr.translation end) as \"".$lang['name']." (".$lang['id'].")\"";
		}
		$query = "select qm.que_uid,qm.que_id,qm.question,".implode(", ",$queCaseArray)." from question_master qm left outer join question_language_relation qlr on qm.que_uid =qlr.que_uid left outer join language_master lm on lm.id=qlr.lang_id left outer join form_master fm on fm.uid = qm.form_uid where fm.tool_id = ? and qlr.deleted is not true and qm.deleted is not true group by qm.que_uid,qm.que_id,qm.question order by qm.que_id";

		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $tool_id, \PDO::PARAM_STR);
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

	public function exportFormTranslation($tool_id){

		$language = $this->apiDBase->getLanguages();
		$caseArray = array();
		foreach ($language['text'] as $lang) {
			$caseArray[] = "min(CASE WHEN lm.id = '".$lang['id']."' THEN flr.translation end) as \"".$lang['name']." (".$lang['id'].")\"";
		}
		$query = "select fm.uid,fm.id,fm.name, ".implode(", ", $caseArray)." from form_master fm join form_language_relation flr on fm.uid =flr.form_uid left outer join language_master lm on lm.id=flr.lang_id where fm.tool_id = ? and flr.deleted is not true and fm.deleted is not true and lm.deleted is not true group by fm.id,fm.name,fm.uid order by fm.id";

		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $tool_id, \PDO::PARAM_STR);
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

	public function exportOptionTranslation(){

		$language = $this->apiDBase->getLanguages();
		$caseArray = array();
		foreach ($language['text'] as $lang) {
			$caseArray[] = "min(CASE WHEN lm.id = '".$lang['id']."' THEN olr.translation end) as \"".$lang['name']." (".$lang['id'].")\"";
		}
		$query = "select om.option_uid,om.option_id,om.option_name, ".implode(", ", $caseArray)." from option_master om join option_language_relation olr on om.option_uid =olr.option_uid left outer join language_master lm on lm.id=olr.lang_id where olr.deleted is not true and om.deleted is not true group by om.id,om.option_id,om.option_name,om.option_uid order by om.id";

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

	function deleteQuestionLanguage($que_id,$lang_id){
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
		$query = "UPDATE question_language_relation SET deleted = true,deleted_date = to_timestamp(".time()."),deleted_by = '".$_SESSION['userid']."' WHERE lang_id $where and que_uid = ? and deleted is not true";
		
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
		$statement->bindParam($i, $que_id, \PDO::PARAM_STR);
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

	function deleteFormLanguage($form_uid,$form_lid){
		$notin = array();
		foreach ($form_lid as $key => $value) {
			if(!empty($value)){
				$notin[] = '?';
			}
		}
		if(sizeof($notin) < 1){
			foreach ($form_lid as $key => $value) {
				$notin[] = '?';
			}
			$notinString = implode(',', $notin);
			$where = " in ($notinString) ";
			$notin = array();
		} else{
			$notinString = implode(',', $notin);
			$where = " not in ($notinString) ";
		}
		$query = "UPDATE form_language_relation SET deleted = true,deleted_date = to_timestamp(".time()."),deleted_by = '".$_SESSION['userid']."' WHERE lang_id $where and form_uid = ? and deleted is not true";
		
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$i = 1;
		if(sizeof($notin) < 1){
			foreach ($form_lid as $key => $value) {
				$statement->bindParam($i, intval($key), \PDO::PARAM_INT);
				$i++;
			}
		}else{
			foreach ($form_lid as $key => $value) {
				if(!empty($value)){
					$statement->bindParam($i, intval($key), \PDO::PARAM_INT);
					$i++;
				}	
			}
		}
		$statement->bindParam($i, $form_uid, \PDO::PARAM_STR);
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

	public function deleteQuestion($id){

		$query = "UPDATE question_master SET deleted = true, deleted_date = to_timestamp(".time()."),deleted_by = '".$_SESSION['userid']."' WHERE que_uid = ?";

		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $id, \PDO::PARAM_STR);
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