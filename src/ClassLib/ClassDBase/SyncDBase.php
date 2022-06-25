<?php
namespace FES\GEET\ClassLib\ClassDBase;
use FES\GEET\ClassLib\Database;

class SyncDbase extends Database{

	protected $database;
	function __construct(){

		$this->database = new Database;
	}

    function postSyncQuestion($que_id,$que_uid,$form_id,$position,$question,$type,$value,$hint,$value_reuired,$dependency_flag,$condition,$mandatory,$window_type,$form_uid){

        $query = "WITH upsert AS (UPDATE question_master SET que_id=?,que_uid=?,form_id=?,position=?,question=?,type=?,value=?,hint=?,value_required=?,dependency_flag=?,condition=?,mandatory=?,window_type=?,form_uid = ?,update_date=to_timestamp(".time()."),update_by='".$_SESSION['userid']."' where que_uid = ? RETURNING que_uid) insert into question_master (que_id,que_uid,form_id,position,question,type,value,hint,value_required,dependency_flag,condition,mandatory,window_type,form_uid,create_date,create_by) select ?,?,?,?,?,?,?,?,?,?,?,?,?,?,to_timestamp(".time()."),'".$_SESSION['userid']."' where NOT EXISTS (SELECT * FROM upsert) ";

        $this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1,$que_id, \PDO::PARAM_INT);
		$statement->bindParam(2,$que_uid, \PDO::PARAM_STR);
		$statement->bindParam(3,$form_id, \PDO::PARAM_INT);
		$statement->bindParam(4,$position, \PDO::PARAM_INT);
		$statement->bindParam(5,$question, \PDO::PARAM_STR);
		$statement->bindParam(6,$type, \PDO::PARAM_STR);
		$statement->bindParam(7,$value, \PDO::PARAM_STR);
		$statement->bindParam(8,$hint, \PDO::PARAM_STR);
		$statement->bindParam(9,$value_reuired, \PDO::PARAM_STR);
		$statement->bindParam(10,$dependency_flag, \PDO::PARAM_INT);
		$statement->bindParam(11,$condition, \PDO::PARAM_STR);
		$statement->bindParam(12,$mandatory, \PDO::PARAM_INT);
		$statement->bindParam(13,$window_type, \PDO::PARAM_STR);
		$statement->bindParam(14,$form_uid, \PDO::PARAM_STR);
		$statement->bindParam(15,$que_uid, \PDO::PARAM_STR);
		$statement->bindParam(16,$que_id, \PDO::PARAM_INT);
		$statement->bindParam(17,$que_uid, \PDO::PARAM_STR);
		$statement->bindParam(18,$form_id, \PDO::PARAM_INT);
		$statement->bindParam(19,$position, \PDO::PARAM_STR);
		$statement->bindParam(20,$question, \PDO::PARAM_STR);
		$statement->bindParam(21,$type, \PDO::PARAM_STR);
		$statement->bindParam(22,$value, \PDO::PARAM_STR);
		$statement->bindParam(23,$hint, \PDO::PARAM_STR);
		$statement->bindParam(24,$value_reuired, \PDO::PARAM_STR);
		$statement->bindParam(25,$dependency_flag, \PDO::PARAM_INT);
		$statement->bindParam(26,$condition, \PDO::PARAM_STR);
		$statement->bindParam(27,$mandatory, \PDO::PARAM_INT);
		$statement->bindParam(28,$window_type, \PDO::PARAM_STR);
		$statement->bindParam(29,$form_uid, \PDO::PARAM_STR);
		try{
			$statement->execute();
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$result = $statement->rowCount();
			if($result < 0){
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

	function postSyncForm($id,$uid,$name,$position,$tool_id,$sync_api,$survey_name){

        $query = "WITH upsert AS (UPDATE form_master SET id = ?, uid = ?, name = ?, position = ?, tool_id = ?, sync_api = ?, survey_name = ?,update_date=to_timestamp(".time()."),update_by='".$_SESSION['userid']."' where uid = ?  RETURNING uid) insert into form_master (id,uid,name,position,tool_id,sync_api,survey_name,create_date,create_by) select ?,?,?,?,?,?,?,to_timestamp(".time()."),'".$_SESSION['userid']."'  where NOT EXISTS (SELECT * FROM upsert) ";

        $this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1,$id, \PDO::PARAM_INT);
		$statement->bindParam(2,$uid, \PDO::PARAM_STR);
		$statement->bindParam(3,$name, \PDO::PARAM_STR);
		$statement->bindParam(4,$position, \PDO::PARAM_INT);
		$statement->bindParam(5,$tool_id, \PDO::PARAM_STR);
		$statement->bindParam(6,$sync_api, \PDO::PARAM_STR);
		$statement->bindParam(7,$survey_name, \PDO::PARAM_STR);
		$statement->bindParam(8,$uid, \PDO::PARAM_STR);
		$statement->bindParam(9,$id, \PDO::PARAM_INT);
		$statement->bindParam(10,$uid, \PDO::PARAM_STR);
		$statement->bindParam(11,$name, \PDO::PARAM_STR);
		$statement->bindParam(12,$position, \PDO::PARAM_INT);
		$statement->bindParam(13,$tool_id, \PDO::PARAM_STR);
		$statement->bindParam(14,$sync_api, \PDO::PARAM_STR);
		$statement->bindParam(15,$survey_name, \PDO::PARAM_STR);
		try{
			$statement->execute();
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$result = $statement->rowCount();
			if($result < '0'){
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

	function postSyncOption($option_id,$option_uid,$que_uid,$option_name){

        $query = "WITH upsert AS (UPDATE option_master SET id = ?, uid = ?, name = ?, position = ?, sync_api = ?, survey_name = ?,update_date=to_timestamp(".time()."),update_by='".$_SESSION['userid']."' where uid = ?  RETURNING uid) insert into option_master (id,uid,name,position,sync_api,survey_name,create_date,create_by) select ?,?,?,?,?,?,to_timestamp(".time()."),'".$_SESSION['userid']."'  where NOT EXISTS (SELECT * FROM upsert) ";

        $this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1,$id, \PDO::PARAM_INT);
		$statement->bindParam(2,$uid, \PDO::PARAM_STR);
		$statement->bindParam(3,$name, \PDO::PARAM_STR);
		$statement->bindParam(4,$position, \PDO::PARAM_INT);
		$statement->bindParam(5,$sync_api, \PDO::PARAM_STR);
		$statement->bindParam(6,$survey_name, \PDO::PARAM_STR);
		$statement->bindParam(7,$uid, \PDO::PARAM_STR);
		$statement->bindParam(8,$id, \PDO::PARAM_INT);
		$statement->bindParam(9,$uid, \PDO::PARAM_STR);
		$statement->bindParam(10,$name, \PDO::PARAM_STR);
		$statement->bindParam(11,$position, \PDO::PARAM_INT);
		$statement->bindParam(12,$sync_api, \PDO::PARAM_STR);
		$statement->bindParam(13,$survey_name, \PDO::PARAM_STR);
		try{
			$statement->execute();
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$result = $statement->rowCount();
			if($result < '0'){
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

	function getSyncTime(){
		$query = "Insert into sync_time_master (sync_time,sync_user) values (to_timestamp(".time()."),'".$_SESSION['userid']."')";

        $this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
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