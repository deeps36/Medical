<?php
namespace FES\GEET\ClassLib\ClassDBase;
use FES\GEET\ClassLib\Database;

class ToolsDbase extends Database{

	protected $database;
	
	function __construct(){

		$this->database = new Database;
	}

	public function displayTool($id = null){
		$where = '';
    	if($id !== null){
    		$where = ' id = ? and ';
    	}
		$query = "select id,uid as tool_uid,toolname,tool_desc,tool_url,create_date from tool_master where $where deleted is not true order by toolname";

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

	public function addTool($tool,$tooldesc,$toolurl){
		
		$query = "INSERT INTO tool_master(toolname,tool_desc,tool_url,create_date,create_by) VALUES(?,?,?,to_timestamp(".time()."),'".$_SESSION['userid']."') returning id";

		//$query = "WITH rows as (INSERT INTO tool_master(toolname,tool_desc,tool_url,create_date,create_by) VALUES (?,?,?,to_timestamp(".time()."),'".$_SESSION['userid']."') RETURNING id ) INSERT INTO tools_label_relation(tool_id,label_id,create_date,create_by) SELECT id,?,to_timestamp(".time()."),'".$_SESSION['userid']."' FROM rows";
		
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $tool, \PDO::PARAM_STR);
		$statement->bindParam(2, $tooldesc, \PDO::PARAM_STR);
		$statement->bindParam(3, $toolurl, \PDO::PARAM_STR);
		//$statement->bindParam(4, intval($label_id), \PDO::PARAM_INT);
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

		$query = " Insert into tools_label_relation(tool_id, label_id, create_date, create_by) values (?, ?,to_timestamp(".time()."),'".$_SESSION['userid']."')";

		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $tool_id, \PDO::PARAM_INT);
		$statement->bindParam(2, $label_id, \PDO::PARAM_INT);
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
	
	public function postUpdateTool($id,$tool,$tooldesc,$toolurl){

		$query = "UPDATE tool_master SET toolname = ?,tool_desc = ?, tool_url = ?, update_date = to_timestamp(".time()."),update_by = '".$_SESSION['userid']."' WHERE id = ?";

		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1,$tool, \PDO::PARAM_STR);
		$statement->bindParam(2,$tooldesc, \PDO::PARAM_STR);
		$statement->bindParam(3,$toolurl, \PDO::PARAM_STR);
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

	public function deleteTool($id){

		$query = "UPDATE tool_master SET deleted = true,deleted_date = to_timestamp(".time()."),deleted_by = '".$_SESSION['userid']."' WHERE id = ?";

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

	function getLabelByTool($tool_id){

		$query = "SELECT tlr.label_id,lm.id,lm.labelname from tools_label_relation tlr inner join tool_master tm on tlr.tool_id = tm.id
		inner join label_master lm on tlr.label_id = lm.id where tm.id = ? and tlr.deleted is not true order by tlr.label_id asc";

		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, intval($tool_id), \PDO::PARAM_INT);
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

	public function getLabel(){

		$query = "select id,labelname from label_master where deleted is not true order by labelname asc";

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

	public function updateToolLabel($tool_id,$label_id){
		$query = "insert into tools_label_relation (tool_id ,label_id,create_date,create_by) select ?,?,to_timestamp(".time()."),'".$_SESSION['userid']."' where not exists (select * from tools_label_relation where tool_id = ? and label_id = ? and deleted is not true)";

		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);	
		$statement->bindParam(1,intval($tool_id), \PDO::PARAM_INT);
		$statement->bindParam(2,intval($label_id), \PDO::PARAM_INT);
		$statement->bindParam(3,intval($tool_id), \PDO::PARAM_INT);
		$statement->bindParam(4,intval($label_id), \PDO::PARAM_INT);
		// $statement->bindParam(5,intval($tool_id), \PDO::PARAM_INT);
		// $statement->bindParam(6,intval($label_id), \PDO::PARAM_INT);		
			
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

	public function deleteToolLabel($tool_id,$label_id){
		$notin = array();
		$label_id = $_POST['labelname'];
		foreach ($label_id as $label) {
			$notin[] = '?';
		}
		$notinString = implode(',', $notin);
		
		$query = "UPDATE tools_label_relation SET deleted = true,deleted_date = to_timestamp(".time()."), deleted_by = '".$_SESSION['userid']."' WHERE label_id not in ($notinString) and tool_id = ? and deleted is not true";
		
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$i = 1;
		foreach ($label_id as $id) {
			$statement->bindParam($i, intval($id), \PDO::PARAM_INT);
			$i++;
		}
		$statement->bindParam($i, intval($tool_id), \PDO::PARAM_INT);
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