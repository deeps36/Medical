<?php
namespace FES\GEET\ClassLib\ClassDBase;
use FES\GEET\ClassLib\Database;


/**
* UserDBase
*/
class UserDBase extends Database
{
	private $db;
	// public $getFields;

	function __construct()
	{
		$this->database = new Database;
		
		// $this->getFields = $this->db->getFields('user_master', null);
	}

	
	public function login($username, $password)
	{
		//$query = "select * from user_master um, user_roles_relation ur where (um.name = '".$username. "' or um.user_id = '".$username. "') and password = '".$password."' and  um.user_id=ur.user_id"; // login using user_id or user name
		$query = "select um.user_id, um.name, um.designation, um.landline_number, um.mob_number, um.email, um.address, um.start_date, um.end_date, um.password, um.default_village, um.regular_login, um.temp_blocked, um.temp_blocked_time, um.create_by, um.super_admin, ur.role_id, org.org_id, orgm.name as orgname from user_master um left join user_roles_relation ur on um.user_id = ur.user_id left join organization_user_relation org on um.user_id = org.user_id left join organization_master orgm on org.org_id = orgm.id where um.user_id = ? and password = ?";
		
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $username, \PDO::PARAM_STR);
		$statement->bindParam(2, $password, \PDO::PARAM_STR);
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

		//$response = $this->db->coreConnection($query);
		//return $response;
	}

	function addRegularLogin($userId){
		$query = "update user_master set regular_login = true where user_id = ?";
		$this->db = $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $userId, \PDO::PARAM_STR);
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
			//$response['error'] = error_log("Got sql error at line ".__LINE__." in ".__FILE__.". ".$e->getMessage());
			return $response;
		} finally {
			$this->db = null;
		}
	}

	function blockUser($userid, $time){
		$query = "update user_master set temp_blocked = true, temp_blocked_time = to_timestamp(".$time.") where user_id = ?";
		$this->db = $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $userid, \PDO::PARAM_STR);
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
			//$response['error'] = error_log("Got sql error at line ".__LINE__." in ".__FILE__.". ".$e->getMessage());
			return $response;
		} finally {
			$this->db = null;
		}
	}

	function updateBlockedUser($userid, $time){

		// unblock user if blocked time is over

		$query = "update user_master set temp_blocked = false, temp_blocked_time = NULL where user_id = ? and EXTRACT(epoch FROM (current_timestamp - temp_blocked_time::timestamp)) >= 3600"; // 3600 seconds  = 1 hour
		$this->db = $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $userid, \PDO::PARAM_STR);
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
			//$response['error'] = error_log("Got sql error at line ".__LINE__." in ".__FILE__.". ".$e->getMessage());
			return $response;
		} finally {
			$this->db = null;
		}
	}
	
	function checkTokenSessionExists($deviceid, $userid, $tokenid = ""){
		$column = "";
		if($tokenid !== "") $column = " and token_id = ?";
		$query = "select * from token_session where device_id = ? and user_id = ? and (end_date is null or end_date > to_timestamp(".time().")) ".$column;
		
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $deviceid, \PDO::PARAM_STR);
		$statement->bindParam(2, $userid, \PDO::PARAM_STR);
		if($tokenid !== ""){
			$statement->bindParam(3, $tokenid, \PDO::PARAM_STR);
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

		//$response = $this->db->coreConnection($query);
		//return $response;
	}
	
	function addTokenSession($tokenid, $deviceid, $userid){
		$query = "insert into token_session (device_id, token_id, user_id, start_date) values(?, ?, ?, to_timestamp(".time()."))";
		
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $deviceid, \PDO::PARAM_STR);
		$statement->bindParam(2, $tokenid, \PDO::PARAM_STR);
		$statement->bindParam(3, $userid, \PDO::PARAM_STR);
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
	
	function deleteTokenSession($tokenid, $deviceid, $userid){
		$query = "update token_session set end_date = to_timestamp(".time().") where device_id = ? and user_id = ? and token_id = ?";
		$this->db = $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $deviceid, \PDO::PARAM_STR);
		$statement->bindParam(2, $userid, \PDO::PARAM_STR);
		$statement->bindParam(3, $tokenid, \PDO::PARAM_STR);
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
	
	public function getRole($user_id){
		$query = "select * from user_roles_relation ur where user_id = ?";
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
				echo $_SESSION['userid']."<br>";echo"<pre>";var_dump($statement);echo"</pre>";exit;
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

	public function getGroups($user_id){
		$query = "select * from user_group_relation ur where user_id = ?";
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

	public function getAdminUser($user_id){
		$query = "select u.* "
            . "from user_master as u "
            . "where u.user_id = ?";
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

	public function getAllAdminUsers(){
		if($_SESSION['super_admin']){
			$query = "select a.*, string_agg(c.role_name, ', ' order by c.role_name) as role, d.name as org from user_master a left join roles_master c on c.role_id in (select role_id from user_roles_relation b where a.user_id = b.user_id and role_id not in (1,17)) left join organization_master d on d.id = (select org_id from organization_user_relation e where a.user_id = e.user_id) group by a.user_id, d.name";
		} else{
			$query = "select a.*, string_agg(c.role_name, ', ' order by c.role_name) as role, d.name as org from user_master a left join roles_master c on c.role_id in (select role_id from user_roles_relation b where a.user_id = b.user_id and role_id not in (1,17)) left join organization_master d on d.id = (select org_id from organization_user_relation e where a.user_id = e.user_id) where a.create_by = ? group by a.user_id, d.name ";//.$_SESSION['userid']."'";
		}
		//$query = "select * from user_master";
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		if(!$_SESSION['super_admin']){
			$statement->bindParam(1, $_SESSION['userid'], \PDO::PARAM_STR);
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

	public function getAllRoles()
	{
		if($_SESSION['super_admin']){
			$query = "select role_id, role_name, role_desc from roles_master order by role_name";
		} else{
			$query = "select role_id, role_name, role_desc from roles_master where role_id not in (16, 18, 19, 20, 21) order by role_name";	
		}
		
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
	
	function getUserRoles($user_id){
		$query = "select * from user_roles_relation where user_id = ?";
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

	// public function getAllGroups()
	// {
	// 	$query = "select group_id, group_name, group_desc from group_master order by group_name";
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
	// }
	

	public function getUserSpecificAccessPolicy($roleId)
	{
		$roleIdArr = explode(",", $roleId);
		$placeHolders = implode(',', array_fill(0, count($roleIdArr), '?'));
		$query = "select accesspolicy from access_policy where role_id in (".$placeHolders.")";
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$i = 1;
		foreach($roleIdArr as $value){
		    $statement->bindParam($i, intval($value));
		    $i++;
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
	
	function getUserSpecificPageAccess($roleId){
		$placeHolders = implode(',', array_fill(0, count($roleId), '?'));
		$query = "select string_agg(pa.page_id::text, ' delimit ') as page_id, string_agg(pm.page_name, ' delimit ') as page_name, string_agg(pm.page_link, ' delimit ') as page_link, pcm.page_category_id , pcm.page_category_name from page_access pa, page_category_master pcm, page_master pm where pa.page_id = pm.page_id and pa.page_category_id = pcm.page_category_id and cast(pa.role_id as integer) in (".$placeHolders.") group by pcm.page_category_id, pcm.page_category_name";
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$i = 1;
		foreach($roleId as $value){
		    $statement->bindParam($i, intval($value));
		    $i++;
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
	
	function getAllPages(){
		$query = "select string_agg(pm.page_id::text, ' delimit ') as page_id, string_agg(pm.page_name, ' delimit ') as page_name, string_agg(pm.page_link, ' delimit ') as page_link, pcm.page_category_id , pcm.page_category_name from  page_category_master pcm, page_master pm where pm.page_category_id = pcm.page_category_id group by pcm.page_category_id, pcm.page_category_name order by pcm.weight";
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

	
	public function updateAccessPolicy($data, $role)
	{
		$access = implode(",", $data);
		if($this->checkRoleExists($role))
		{
			$query = "update access_policy set accesspolicy = ? where role_id = ?";
			$this->db = $this->database->coreConnection();
			$statement = $this->db->prepare($query);
			$statement->bindParam(1, $access, \PDO::PARAM_STR);
			$statement->bindParam(2, intval($role), \PDO::PARAM_INT);
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
		}else{
			$query = "insert into access_policy values(?, ?)";
			$this->db = $this->database->coreConnection();
			$statement = $this->db->prepare($query);
			$statement->bindParam(1, intval($role), \PDO::PARAM_INT);
			$statement->bindParam(2, $access, \PDO::PARAM_STR);
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
				return $response['text'];
			} catch(\PDOException $e) {
				error_log("Got sql error at line ".__LINE__." in ".__FILE__.". ".$e->getMessage());
				$response['responseType'] = '-1';
				$response['text'] = 'Error - query failed';
				$response['error'] = 'Error - query failed';
				return $response['text'];
			} finally {
				$this->db = null;
			}	
		}
	}
	

	public function checkRoleExists($role)
	{
		$query = "select count(*) from access_policy where role_id = ?";
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, intval($role), \PDO::PARAM_INT);
		try{
			$statement->execute();
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$result = $statement->fetchAll();
			if(sizeof($result) <= 0){
				$response['responseType'] = '2';
				$response['text'] = array();
				$response['text'][0] = array();
				$response['text'][0]['count'] = 0;
			} else{
				$response['responseType'] = '1';
				$response['text'] = $result;
			}
			return $response['text'][0]['count'];
		} catch(\PDOException $e) {
			error_log("Got sql error at line ".__LINE__." in ".__FILE__.". ".$e->getMessage());
			$response['responseType'] = '-1';
			$response['text'] = array();
			$response['text'][0] = array();
			$response['text'][0]['count'] = 0;
			$response['error'] = 'Error - query failed';
			return $response['text'][0]['count'];
		} finally {
			$this->db = null;
		}
	}

	function postNewUser($user_id, $name, $designation, $landline_number, $mobile_number, $email, $address, $password) {

		$query = "insert into user_master (user_id, name, designation, landline_number, mob_number, email, address, start_date, password, create_by) values (?, ?, ?, ?, ?, ?, ?, date(now()), ?,  '".$_SESSION['userid']."')  RETURNING user_id";
		$this->db = $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $user_id, \PDO::PARAM_STR);
		$statement->bindParam(2, $name, \PDO::PARAM_STR);
		$statement->bindParam(3, $designation, \PDO::PARAM_STR);
		$statement->bindParam(4, intval($landline_number), \PDO::PARAM_INT);
		$statement->bindParam(5, intval($mobile_number), \PDO::PARAM_INT);
		$statement->bindParam(6, $email, \PDO::PARAM_STR);
		$statement->bindParam(7, $address, \PDO::PARAM_STR);
		$statement->bindParam(8, $password, \PDO::PARAM_STR);
		try{
			$statement->execute();
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$result = $statement->rowCount();
			$data = $statement->fetchAll();
			if($result <= 0 ){
				$response['responseType'] = '2';
				$response['text'] = "No data affected.";
			} else{
				$response['responseType'] = '1';
				$response['text'] = $data;
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

	/*function postNewUserRoles($user_id, $roles){
		$values = array();
		foreach($roles as $role) {
			$values[] = "('".$user_id."',".$role.", date(now()))";
		}

		$query = 'insert into user_roles_relation (user_id, role_id, start_date) values '.implode(",", $values);
		$result = $this->db->coreConnection($query); //print_r($result); exit;
		return $result;
	}*/

	function postNewUserRoles($user_id, $roles){
		$values = array();
		foreach($roles as $role) {
			//$values[] = "('".$user_id."',".$role.", date(now()))";
			if($role == 20){ //20 is super admin
				$this->updateSuperAdminFlag($user_id, 'true');
			}
			$query = "insert into user_roles_relation (user_id, role_id, start_date) values (?, ?, date(now()))";
			$this->db = $this->database->coreConnection();
			$statement = $this->db->prepare($query);
			$statement->bindParam(1, $user_id, \PDO::PARAM_STR);
			$statement->bindParam(2, intval($role), \PDO::PARAM_INT);
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
		}
		return $response;
	}

	function updateSuperAdminFlag($user_id, $add = 'false'){
		$query = "update user_master set super_admin = $add where user_id = ?";
		$this->db = $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $user_id, \PDO::PARAM_STR);
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
	}

	/*
	function postNewUserGroups($user_id, $groups){
		$values = array();
		foreach($groups as $group) {
			$values[] = "('".$user_id."',".$group.", date(now()))";
		}

		$query = 'insert into user_group_relation (user_id, group_id, start_date) values '.implode(",", $values);
		$result = $this->db->coreConnection($query);
		return $result;
	}*/


	function postNewUserOrganization($user_id, $organizations){
		$values = array();
		$query = "insert into organization_user_relation (user_id, org_id, create_by, create_date) values (?, ?, '".$_SESSION['userid']."', date(now()))";
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
		$query = "WITH upsert AS (UPDATE organization_user_relation SET org_id=?, update_date = date(now()), update_by = '".$_SESSION['userid']."' WHERE user_id=? RETURNING *) INSERT INTO organization_user_relation (org_id, user_id, create_date, create_by) SELECT ?, ?, date(now()), '".$_SESSION['userid']."' WHERE NOT EXISTS (SELECT * FROM upsert)";
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

	function checkEmailExists($email) {
		$query = "select count(user_id) as total_rows from user_master where email = ?";
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $email, \PDO::PARAM_STR);
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

	function checkUserIdExists($user_id) {
		$query = "select count(user_id) as total_rows from user_master where user_id = ?";
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

	function updateUser($user_id, $name, $designation, $landline_number, $mobile_number, $address) {
		$query = 'update user_master set name = ?, designation = ?, landline_number = ?, mob_number = ?, address = ? WHERE user_id = ?';
		$this->db = $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $name, \PDO::PARAM_STR);
		$statement->bindParam(2, $designation, \PDO::PARAM_STR);
		$statement->bindParam(3, intval($landline_number), \PDO::PARAM_INT);
		$statement->bindParam(4, intval($mobile_number), \PDO::PARAM_INT);
		$statement->bindParam(5, $address, \PDO::PARAM_STR);
		$statement->bindParam(6, $user_id, \PDO::PARAM_STR);
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

	function resetPassword($user_id, $password) {
		$query = 'update user_master set password = ? where user_id = ?';
		$this->db = $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $password, \PDO::PARAM_STR);
		$statement->bindParam(2, $user_id, \PDO::PARAM_STR);
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

	// profile

	// role
	function postNewRole($role_name, $role_desc) {
		$query = 'insert into roles_master (role_name, role_desc) values (?, ?)';
		$this->db = $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $role_name, \PDO::PARAM_STR);
		$statement->bindParam(2, $role_desc, \PDO::PARAM_STR);
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

	public function getRoleByID($role_id){
		$query = "select * from roles_master where role_id = ?";
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, intval($role_id), \PDO::PARAM_INT);
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

	function updateRole($role_id, $role_desc) {
		$query = 'update roles_master set role_desc = ? WHERE role_id = ?';
		$this->db = $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $role_desc, \PDO::PARAM_STR);
		$statement->bindParam(2, intval($role_id), \PDO::PARAM_INT);
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

	function deleteUserRoles($user_id, $roles = array()){ 
		$placeHolders = implode(',', array_fill(0, count($roles), '?'));
		$where = (sizeof($roles) > 0) ? " and role_id in (".$placeHolders.")" : "";

		$query = "delete from user_roles_relation where user_id = ?".$where;
		$this->db = $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $user_id, \PDO::PARAM_STR);
		$i = 2;
		foreach($roles as $value){
			if($value == 20){ //20 is super admin
				$this->updateSuperAdminFlag($user_id, 'false');
			}
		    $statement->bindParam($i, intval($value), \PDO::PARAM_INT);
		    $i++;
		}
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
	
	// Location Hierarchy


    
    // Location related queries
    

	function addWebSession($userId, $ip, $user_agent, $action, $status){
		$query = "insert into web_sessions (user_id, ip_address, user_agent, action_performed, status) values(?, ?, ?, ?, ?)";
		$this->db = $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $userId, \PDO::PARAM_STR);
		$statement->bindParam(2, $ip, \PDO::PARAM_STR);
		$statement->bindParam(3, $user_agent, \PDO::PARAM_STR);
		$statement->bindParam(4, $action, \PDO::PARAM_STR);
		$statement->bindParam(5, $status, \PDO::PARAM_STR);
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

	function getPageCategories(){
		$query = "select * from page_category_master order by page_category_name";
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

	function getPageCategory($id){
		$query = "select * from page_category_master where page_category_id = ?";
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $id, \PDO::PARAM_STR);
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

	function updatePageCategory($category){
		$query = "update page_category_master set page_category_name = ?, weight = ? where page_category_id = ?";
		$this->db = $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $category['cname'], \PDO::PARAM_STR);
		$statement->bindParam(2, intval($category['weight']), \PDO::PARAM_INT);
		$statement->bindParam(3, intval($category['c_id']), \PDO::PARAM_INT);
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

	function postPageCategory($category){
		$query = "insert into page_category_master (page_category_name,weight) values (?, ?)";
		$this->db = $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $category['cname'], \PDO::PARAM_STR);
		$statement->bindParam(2, intval($category['weight']), \PDO::PARAM_INT);
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

	function getAllMenuPages(){
		$query = "select a.*, b.page_category_name from page_master a, page_category_master b where a.page_category_id = b.page_category_id order by a.page_category_id";
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

	function getMenuPage($id){
		$query = "select * from page_master where page_id = ?";
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $id, \PDO::PARAM_STR);
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

	function updateMenuPage($page){
		$column = "";
		if($page['for_mobile']=="Yes"){
			$column = ", mobile_action = '".$page['action']."'";
		} else{
			$column = ", mobile_action = null";
		}
		$query = "update page_master set page_name = ?, page_category_id = ?, page_link = ?, for_mobile = ".($page['for_mobile']=='Yes'?'True':'False')." $column where page_id = ?";
		$this->db = $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $page['pname'], \PDO::PARAM_STR);
		$statement->bindParam(2, intval($page['category']), \PDO::PARAM_INT);
		$statement->bindParam(3, $page['plink'], \PDO::PARAM_STR);
		if($page['for_mobile']=="Yes"){
			$statement->bindParam(4, $page['action'], \PDO::PARAM_STR);
			$statement->bindParam(5, intval($page['pid']), \PDO::PARAM_INT);
		} else{
			$statement->bindParam(4, intval($page['pid']), \PDO::PARAM_INT);
		}
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

	function postPage($page){
		$column = $columnVal = "";
		if($page['for_mobile']=="Yes"){
			$page['for_mobile'] = 'true';
			$column = ", mobile_action";	
			$columnVal = ", ?";
		} else{
			$page['for_mobile'] = 'false';
		}
		$query = "insert into page_master (page_name, page_category_id, page_link, for_mobile $column) values (?,?,?,? $columnVal )";
		$this->db = $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $page['pname'], \PDO::PARAM_STR);
		$statement->bindParam(2, intval($page['category']), \PDO::PARAM_INT);
		$statement->bindParam(3, $page['plink'], \PDO::PARAM_STR);
		$statement->bindParam(4, $page['for_mobile'], \PDO::PARAM_BOOL);
		if($page['for_mobile']=="Yes"){
			$statement->bindParam(5, $page['action'], \PDO::PARAM_STR);
		}
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

	function addUserPerformedActions($userid, $action, $status, $submittedParams, $ip, $agent){
		$query = "insert into user_performed_actions (user_id, action_performed, status, parameters_submitted, timestamp, ip_address, user_agent) values (?, ?, ?, ?, to_timestamp(".time()."), ?, ?)";
		$this->db = $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $userid, \PDO::PARAM_STR);
		$statement->bindParam(2, $action, \PDO::PARAM_STR);
		$statement->bindParam(3, $status, \PDO::PARAM_STR);
		$statement->bindParam(4, $submittedParams, \PDO::PARAM_STR);
		$statement->bindParam(5, $ip, \PDO::PARAM_STR);
		$statement->bindParam(6, $agent, \PDO::PARAM_STR);
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

	function checkPasswordHistory($userId){
		$query = "select * from user_password_history where user_id = ? order by timestamp DESC limit 3";
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $userId, \PDO::PARAM_STR);
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

	function addPasswordHistory($userId, $password, $create_by){
		$query = "insert into user_password_history (user_id, password, create_by) values (?, ?, ?)";
		$this->db = $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $userId, \PDO::PARAM_STR);
		$statement->bindParam(2, $password, \PDO::PARAM_STR);
		$statement->bindParam(3, $create_by, \PDO::PARAM_STR);
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
			$query = "select id, name from organization_master where id = (select org_id from organization_user_relation where user_id = ?) order by name";
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

	function getOrganizationUpdate($id){
		$query = "select id, name from organization_master where id = ?";
		$this->db= $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $id, \PDO::PARAM_INT);
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

	function postUpdateOrganization($id, $name){
		$query = "update organization_master set name = ?, update_by = '".$_SESSION['userid']."', update_date = date(now()) where id = ?";
		$this->db = $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $name, \PDO::PARAM_STR);
		$statement->bindParam(2, intval($id), \PDO::PARAM_INT);
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
			//$response['error'] = error_log("Got sql error at line ".__LINE__." in ".__FILE__.". ".$e->getMessage());
			return $response;
		} finally {
			$this->db = null;
		}
	}

	function postNewOrganization($name){
		$query = "insert into organization_master (name, create_by, create_date) values(?, '".$_SESSION['userid']."', date(now()))";
		$this->db = $this->database->coreConnection();
		$statement = $this->db->prepare($query);
		$statement->bindParam(1, $name, \PDO::PARAM_STR);
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
			//$response['error'] = error_log("Got sql error at line ".__LINE__." in ".__FILE__.". ".$e->getMessage());
			return $response;
		} finally {
			$this->db = null;
		}
	}

}
