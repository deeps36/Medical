<?php
namespace FES\GEET\ClassLib\ClassDBase;
use FES\GEET\ClassLib\Database;

class ApiDbase extends Database{

	protected $database;
	
	function __construct(){

		$this->database = new Database('multi_lang_final');

	}

	function index(){
		$this->rest->post(json_encode(array('value')));
  	}

    function getApis($id = null){
    	$where = '';
    	if($id !== null){
    		$where = ' id = ? and '; 
    	}
        $query = "select id,apiname,apiurl,create_date from api_master where $where deleted is not true ";
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

    function addApi($apiname,$apiurl){

        $query = "INSERT INTO api_master(apiname,apiurl,create_date,create_by) VALUES(?,?,to_timestamp(".time()."),'".$_SESSION['userid']."')";
		
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $apiname, \PDO::PARAM_STR);
		$statement->bindParam(2, $apiurl, \PDO::PARAM_STR);
		
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

	public function postUpdateApi($id,$apiname,$apiurl){

		$query = "UPDATE api_master SET apiname = ?, apiurl = ?, update_date = to_timestamp(".time()."), update_by = '".$_SESSION['userid']."' WHERE id = ?";

		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1,$apiname, \PDO::PARAM_STR);
		$statement->bindParam(2,$apiurl, \PDO::PARAM_STR);
		$statement->bindParam(3, intval($id), \PDO::PARAM_INT);
		
			
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

    public function deleteApi($id){

		$query = "UPDATE api_master SET deleted = true,deleted_date = to_timestamp(".time()."),deleted_by = '".$_SESSION['userid']."' WHERE id = ?";

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

	function getUser(){

		$query="select user_id,name from user_master_services where temp_blocked is not true";
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

	function getApiByUser($user_id){

		$query = "select api_id,user_id from api_access where user_id = ? and deleted is not true";

		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $user_id, \PDO::PARAM_STR);
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

	function updateApiAccess($user_id,$api_id){

		$query = "insert into api_access (api_id,user_id,create_date,create_by) select ?,?,to_timestamp(".time()."),'".$_SESSION['userid']."' where not exists (select * from api_access where api_id = ? and user_id = ? and deleted is not true)";
			
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1,intval($api_id), \PDO::PARAM_INT);
		$statement->bindParam(2,$user_id, \PDO::PARAM_STR);
		$statement->bindParam(3,intval($api_id), \PDO::PARAM_INT);
		$statement->bindParam(4,$user_id, \PDO::PARAM_STR);
		/*$statement->bindParam(5,intval($api_id), \PDO::PARAM_INT);
		$statement->bindParam(6,$user_id, \PDO::PARAM_STR);
*/
		try{
			$statement->execute();
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$result = $statement->rowCount();
			if($result <= 0 ){
				$response['responseType'] = '2';
				$response['text'] = "No data affected.";
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

	function deleteApiAccess($user_id,$api_id){
		$notin = array();
		foreach ($api_id as $api) {
			$notin[] = '?';
		}
		if(sizeof($notin) < 1){
			foreach ($api_id as $api) {
				$notin[] = '?';
			}
			$notinString = implode(',', $notin);
			$where = " in ($notinString) ";
			$notin = array();
		} else{
			$notinString = implode(',', $notin);
			$where = " not in ($notinString) ";
		}
	
		$query = "UPDATE api_access SET deleted = true,deleted_date = to_timestamp(".time()."), deleted_by = '".$_SESSION['userid']."' WHERE api_id $where and user_id = ? and deleted is not true";
		
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$i = 1;
		foreach ($api_id as $id) {
			$statement->bindParam($i, intval($id), \PDO::PARAM_INT);
			$i++;
		}
		$statement->bindParam($i, $user_id, \PDO::PARAM_STR);
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

	function getToolLabels($tool_uid){
		
		$query = 'select lm.uid as label_uid,lm.id as label_id,lm.labelname from tools_label_relation tlr join label_master lm on tlr.label_id = lm.id join tool_master tm on tm.id = tlr.tool_id where tm.uid = ? and lm.deleted is not true and tlr.deleted is not true';
		
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1,$tool_uid, \PDO::PARAM_STR);
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
			$response['text'] = 'Error - something went wrong while processing your request. Please contact site administrator';
			$response['error'] = 'Error - something went wrong while processing your request. Please contact site administrator';
			return $response;
		} finally {
			$this->db = null;
		}
	}

	function getlabelTranslation($tool_uid){
		
		$query = "select a.uid,a.id,a.labelname, string_agg(c.lang_id::text, '_delimit_') as lang_id, string_agg(d.name, '_delimit_') as languagename,string_agg(c.translation, '_delimit_') as translation from label_master a inner join tools_label_relation b on a.id = b.label_id join tool_master tm on b.tool_id = tm.id and tm.uid = ? inner join label_language_relation c on c.label_id = b.label_id inner join language_master d on c.lang_id = d.id where c.deleted is not true and d.deleted is not true group by a.id,a.labelname";
		
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1,$tool_uid, \PDO::PARAM_STR);
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
			$response['text'] = 'Error - something went wrong while processing your request. Please contact site administrator';
			$response['error'] = 'Error - something went wrong while processing your request. Please contact site administrator';
			return $response;
		} finally {
			$this->db = null;
		}
	}

	function getLanguages(){
        $query="select id, name, lang_desc as description from language_master where deleted is not true order by name";
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

	 function getQuestionsTranslation($tool_uid){
	 	
	 	$query = "select fm.uid as form_uid,fm.tool_id ,fm.name as formname, qm.que_uid as question_uid,qm.id as question_id,qm.question,qm.value,string_agg(qlr.lang_id::text, '_delimit_') as lang_id ,string_agg(lm.name, '_delimit_') as languagename,string_agg(qlr.translation, '_delimit_') as question_translation, string_agg(flr.translation, '_delimit_') as form_translation from question_master qm inner join question_language_relation qlr on qm.que_uid = qlr.que_uid inner join language_master lm on lm.id = qlr.lang_id inner join form_language_relation flr on lm.id = flr.lang_id
			join form_master fm on fm.uid = flr.form_uid and fm.tool_id = ? where 
			qm.deleted is not true group by qm.id,fm.uid,fm.name,fm.id order by
			qm.question";

			$this->db = $this->database->coreConnection();
			$statement = $this->db->prepare($query);
			$statement->bindParam(1,$tool_uid,\PDO::PARAM_STR); 

			try {
				$statement->execute();
				$statement->setFetchMode(\PDO::FETCH_ASSOC);
				$result = $statement->fetchAll();

				if (sizeof($result) <= 0) {

					$response['responseType'] = '2';
					$response['text'] = 'no data available';
						// code...
				}else{
					$response['responseType'] = '1';
					$response['text'] = $result;
				}
				return $response;
				
			} catch (\PDOException $e) {
				error_log("Got sql error at line" .__LINE__. "in" .__FILE__. ".".$e->getMessage());
				$response['responseType'] = '-1';
				$response['text'] = 'Error- query failed';
				$response['error'] = 'Error- query failed';
				return $response;
				
			}finally{
				$this->db =null;
			}


	 } 

		function getApiUser(){
		        
		//$query="SELECT sr_no,user_id,name,mob_number,email,temp_blocked FROM user_master_services order by name";
	     		
		$query = "select a.*,c.name as organization from user_master_services a left join organization_user_service_relation b on a.user_id = b.user_id left join organization_master c on c.id = b.org_id";	
				
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
	function postNewApiUser($user_id,$name,$mob_number,$email,$password){
	$query = "INSERT INTO user_master_services(user_id, name, mob_number, email, start_date, password,create_by) VALUES (?, ?, ?, ?,date(now()),?,'".$_SESSION['userid']."')  RETURNING user_id";

	$this->db = $this->database->coreConnection();
	$statement = $this->db->prepare($query);
	$statement->bindParam(1,$user_id,\PDO::PARAM_STR);
	$statement->bindParam(2,$name,\PDO::PARAM_STR);
	$statement->bindParam(3,$mob_number,\PDO::PARAM_INT);
	$statement->bindParam(4,$email,\PDO::PARAM_STR);
	$statement->bindParam(5,$password,\PDO::PARAM_STR);
	
	try {
		$statement->execute();
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		$result = $statement->fetchAll();
		if (sizeof($result)<=0) {
			$response['responseType'] = '2';
			$response['text'] = 'no data available';
			# code...
		}else{
			$response['responseType']= '1';
			$response['text'] = $result;
   		}
   		return $response;
		
		}catch(\PDOException $e){
			error_log("Got sql error at line" .__LINE__. "in" .__FILE__. "." .$e->getMessage());
			$response['responseType'] = '-1';
			$response['text'] = 'Error - query failed';
			$response['error'] = 'Error - query failed';
			return $response;

		}finally{
			$this->db = null;
		}
	}
	function checkEmail($email){
		$query = "select count(user_id) as total_rows from user_master_services where email = ?";
		$this->db = $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1,$email,\PDO::PARAM_STR);
		try {
			$statement->execute();
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$result = $statement->fetchAll();

			if (sizeof($result)<=0) {
				$response['responseType'] = '2';
				$response['text'] = 'no data available';
				// code...
			}else{
				$response['responseType'] = '1';
				$response['text'] = $result;
			}
			return $response;
			
		} catch (\PDOException $e) {
			error_log("Got sql error at line" .__LINE__. "in" .__FILE__. "." .$e->getMessage());
			$response['responseType'] = '-1';
			$response['text'] = 'Error- query failed';
			$response['error'] = 'Error - query	failed';
			return $response;
			
		}finally{
			$this->db = null;
		}
	}
	function checkUserId($user_id){
		$query = "select count(user_id) as total_rows from user_master_services where user_id = ?";
		$this->db = $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1,$user_id,\PDO::PARAM_STR);
		try {
			$statement->execute();
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$result = $statement->fetchAll();

			if (sizeof($result)<=0) {
				$response['responseType'] = '2';
				$response['text'] = 'no data available';
				// code...
			}else{
				$response['responseType'] = '1';
				$response['text'] = $result;
			}
			return $response;
			
		} catch (\PDOException $e) {
			error_log("Got sql error at line" .__LINE__. "in" .__FILE__. "." .$e->getMessage());
			$response['responseType'] = '-1';
			$response['text'] = 'Error- query failed';
			$response['error'] = 'Error - query	failed';
			return $response;
			
		}finally{
			$this->db = null;
		}

	}
	function getUpdateApiUser($user_id){
		 $query = "select sr_no,user_id ,name,mob_number,email from user_master_services where user_id = ? and temp_blocked is not true ";
        $this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $user_id, \PDO::PARAM_STR);
		
		try {
		$statement->execute();
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		$result = $statement->fetchAll();
		if (sizeof($result)<=0) {
			$response['responseType'] = '2';
			$response['text'] = 'no data available';
			# code...
		}else{
			$response['responseType']= '1';
			$response['text'] = $result;
   		}
   		return $response;
		
		}catch(\PDOException $e){
			error_log("Got sql error at line" .__LINE__. "in" .__FILE__. "." .$e->getMessage());
			$response['responseType'] = '-1';
			$response['text'] = 'Error - query failed';
			$response['error'] = 'Error - query failed';
			return $response;

		}finally{
			$this->db = null;
		}
	}
	function updateApiUser($user_id,$name,$mob_number){
  	//$query = "update user_master_services set name = ?,mob_number = ?,email = ? where user_id = ?";
  	$query = "update user_master_services set name = ?, mob_number = ? where user_id = ?";
  	$this->db = $this->database->coreConnection();
  	$statement = $this->db->prepare($query);
  	$statement->bindParam(1,$name,\PDO::PARAM_STR);
  	$statement->bindParam(2,$mob_number,\PDO::PARAM_INT);
  	//$statement->bindParam(3,$email,\PDO::PARAM_STR);
  	$statement->bindParam(3,$user_id,\PDO::PARAM_STR);
  	//$statement->bindParam(4,intval($sr_no),\PDO::PARAM_INT);
  	try {
  		$statement->execute();
  		$statement->setFetchMode(\PDO::FETCH_ASSOC);
  		$result = $statement->fetchAll();
  		if (sizeof($result)<=0) {
  			$response['responseType'] = '2';
  			$response['text'] = 'no data available';
  			# code...
  		}else{
  			$response['responseType'] = '1';
  			$response['text'] = $result;
  		}
  		return $response;
  		
  	} catch (\PDOException $e) {
  		error_log("Got sql error at line" .__LINE__. "in" .__FILE__."." .$e->getMessage() );
  		$response['responseType'] = '-1';
  		$response['text'] = 'Error - query failed';
  		$response['error'] = 'Error - query failed';
  		return $response;
  		
  	}finally{
  		$this->db = null;
  	}
}
function updateUserApi($user_id,$api_id){
	$query = "insert into api_access (api_id,user_id,create_date,create_by) select ?,?,to_timestamp(".time()."),'".$_SESSION['userid']."' where not exists (select * from api_access where api_id = ? and user_id = ? and deleted is not true) where exists (select * from api_access where api_id = ? and user_id = ? and deleted is not true)";
			
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1,intval($api_id), \PDO::PARAM_INT);
		$statement->bindParam(2,$user_id, \PDO::PARAM_STR);
		$statement->bindParam(3,intval($api_id), \PDO::PARAM_INT);
		$statement->bindParam(4,$user_id, \PDO::PARAM_STR);
		$statement->bindParam(5,intval($api_id), \PDO::PARAM_INT);
		$statement->bindParam(6,$user_id, \PDO::PARAM_STR);

		try{
			$statement->execute();
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$result = $statement->rowCount();
			if($result <= 0 ){
				$response['responseType'] = '2';
				$response['text'] = "No data affected.";
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
function getOrganizations($userId, $superAdmin = false){
		if($superAdmin){
			$query = "select id, name from organization_master order by name";
		} else{
			$query = "select id, name from organization_master where id = (select org_id from organization_user_service_relation where user_id = ?) order by name";
		}
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		if(!$superAdmin){
			$statement->bindParam(1, $userId, \PDO::PARAM_STR);
		}
		try{
			$statement->execute();
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$result = $statement->fetchAll();
			if(sizeof($result) <= 0){
				$response['responseType'] = '2';
				$response['text'] = array();
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

	function postNewUserOrganization($user_id, $organizations){
		$values = array();
		//$query = "insert into organization_user_service_relation (user_id, org_id, create_by, create_date) values (?, ?, '".$_SESSION['userid']."', to_timestamp(".time().")";
		$query = "insert into organization_user_service_relation (user_id, org_id, create_by, create_date) values (?, ?, '".$_SESSION['userid']."',to_timestamp(".time()."))";
		$this->db = $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $user_id, \PDO::PARAM_STR);
		$statement->bindParam(2, intval($organizations), \PDO::PARAM_INT);
		try{
			$statement->execute();
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$result = $statement->rowCount();
			if($result <= 0 ){
				$response['responseType'] = '2';
				$response['text'] = "No data affected.";
			} else{
				$response['responseType'] = '1';
				$response['text'] = $result." row(s) affected.";
			}
		} catch(\PDOException $e) {
			error_log("Got sql error at line ".__LINE__." in ".__FILE__.". ".$e->getMessage());
			$response['responseType'] = '-1';
			$response['text'] = 'Error - query failed';
			$response['error'] = 'Error - query failed';
			return $response;
		} finally {
			$this->db = null;
		}

		return $response;
	}


	function updateUserOrganization($user_id, $organizations){
		$values = array();

		//$query = "update organization_user_relation set org_id = ?, update_by = '".$_SESSION['userid']."', update_date = date(now()) where user_id = ?";
		$query = "WITH upsert AS (UPDATE organization_user_service_relation SET org_id=?, update_date = to_timestamp(".time()."), update_by = '".$_SESSION['userid']."' WHERE user_id=? RETURNING *) INSERT INTO organization_user_service_relation (org_id, user_id, create_date, create_by) SELECT ?, ?, to_timestamp(".time()."), '".$_SESSION['userid']."' WHERE NOT EXISTS (SELECT * FROM upsert)";
		$this->db = $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, intval($organizations), \PDO::PARAM_INT);
		$statement->bindParam(2, $user_id, \PDO::PARAM_STR);
		$statement->bindParam(3, intval($organizations), \PDO::PARAM_INT);
		$statement->bindParam(4, $user_id, \PDO::PARAM_STR);
		try{
			$statement->execute();
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$result = $statement->rowCount();
			if($result <= 0 ){
				$response['responseType'] = '2';
				$response['text'] = "No data affected.";
			} else{
				$response['responseType'] = '1';
				$response['text'] = $result." row(s) affected.";
			}
		} catch(\PDOException $e) {
			error_log("Got sql error at line ".__LINE__." in ".__FILE__.". ".$e->getMessage());
			$response['responseType'] = '-1';
			$response['text'] = 'Error - query failed';
			$response['error'] = 'Error - query failed';
			return $response;
		} finally {
			$this->db = null;
		}

		return $response;
	}

	function getUnblockApiUser($userid){
				$query = "UPDATE user_master_services SET temp_blocked = false WHERE user_id = ?";
				
				$this->db= $this->database->coreConnection();
				$statement = $this->db->prepare($query);
				$statement->bindParam(1, $userid, \PDO::PARAM_STR);
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
					error_log("Got sql error at line" .__LINE__. "in" .__FILE__. "." .$e->getMessage());
					$response['responseType'] = '-1';
					$response['text'] = 'Error - query failed';
					$response['error'] = 'Error - query failed';
					return $response;

				}
		}

	function getBlockApiUser($userid,$time){
			$query = "UPDATE user_master_services SET temp_blocked = true,temp_blocked_time =to_timestamp(".$time.") WHERE user_id = ?";
			$this->db = $this->database->coreConnection();
			$statement = $this->db->prepare($query);
			$statement->bindParam(1,$userid,\PDO::PARAM_STR);

			try {
				$statement->execute();
				$statement->setFetchMode(\PDO::FETCH_ASSOC);
				$result = $statement->rowCount();
				if ($result <= 0) {
					$response['responseType'] = '2';
					$response['text'] = "no data available";
					// code...
				}else{
					$response['responseType'] = '1';
					$response['text'] = $result. "rows affected";
				}
				return $response;
				
			} catch (\PDOException $e) {
				error_log("Got sql error at line" .__LINE__. "in" .__FILE__. "." .$e->getMessage());
				$response['responseType'] ='-1';
				$response['text'] = 'Error- query failed';
				$response['error'] = 'Error- query failed';
				return $response;
			
		}
	}	

}

?>