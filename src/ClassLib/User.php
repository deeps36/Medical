<?php
namespace FES\GEET\ClassLib;
use FES\GEET\ClassLib\GeetMain;
use FES\GEET\ClassLib\Auth;
use FES\GEET\ClassLib\ClassDBase\UserDBase;
use FES\GEET\ClassLib\Email;
use FES\GEET\ClassLib\Utils;
use IcyApril\CryptoLib;
use FES\GEET\ClassLib\ClassDBase\UtilsDBase;
use Gregwar\Captcha\CaptchaBuilder;

// use FES\GEET\ClassLib\DataEntry;
// use FES\GEET\ClassLib\Users;
// use FES\GEET\ClassLib\Scheme;

/**
* 
*/
class User extends GeetMain
{
	private $credentials = array();
	private $database;
	private $dbase;
	private $utilsDbase;
	private $data;
	private $class;
	private $mail;
	private $salt = '0kt5ku8J9tkl025A7dMAIT4IvYi81zq9';

	protected $userRolesId = array();
	protected $userGroupsId = array();
	protected $newRelations;
	protected $oldRelations;

	function __construct($parent = true)
	{
		if($parent){
			parent::__construct();
			parent::__addAction(array("User.login","User.getLogout","User.register"));
		}
		$this->dbase = new UserDBase;
		$this->mail = new Email;
		$this->utilsDbase = new UtilsDBase;
		$this->utils = new Utils;
	}

	function getUserRelations($user_id) {
		$userRoles = $this->dbase->getRole($user_id);

		if($userRoles['responseType'] === '1') {
			foreach($userRoles['text'] as $role) {
				$this->userRolesId[] = $role['role_id'];
			}
		}
	}

	function getLogin()
	{	
		include __DIR__."/../Views/Home/sign-in.php";
	}

	function getLogout()
	{
		session_regenerate_id();
		if(!isset($_SESSION['admin'])){
			$_SESSION['message']['alert'] = "You have already logged out";
			$this->redirect("/");
		}

		$this->dbase->addWebSession($_SESSION['userid'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'], 'Logout', 'Success');

		//Destroy session and cookies
		$_SESSION = array();
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
				$params["path"], $params["domain"],
				$params["secure"], $params["httponly"]
			);
		}
		session_destroy();
		$_SESSION['message']['success'] = "You have successfully logout";
		$this->redirect("/");
	}

	function getRegisterresi()
	{	
		include __DIR__."/../Views/Home/sign-up-resident.php";
	}

	function getRegister()
	{	
		include __DIR__."/../Views/Home/sign-up.php";
	}
	
	function getLoginMobile(){
		include __DIR__."/../../View/Home/sign-up.php";
	}
	
	function getLogoutMobile(){
		if(isset($_POST['userid']) && isset($_POST['tokenid']) && isset($_POST['connectid'])){
			$ifExists = $this->dbase->checkTokenSessionExists($_POST['connectid'], $_POST['userid'], $_POST['tokenid']);
			if($ifExists['responseType'] === "1"){
				$result = $this->dbase->deleteTokenSession($_POST['tokenid'], $_POST['connectid'], $_POST['userid']);
				if($result['responseType'] === "1"){
					$response['responseType'] = '1';
					$response['text'] = 'Logout successful.';
				} else{
					$response['responseType'] = '-1';
					$response['text'] = 'Logout failed.';
				}
			} else{
				$response['responseType'] = '-1';
				$response['text'] = 'Your session is no longer exists.';
			}
		} else{
			$response['responseType'] = '-1';
			$response['text'] = 'Missing parameters';
		}
		echo json_encode($response); 
	}

	function getAdminUsers() {
		$fields = $this->dbase->getAllAdminUsers();
		include __DIR__."/../Views/User/admin_users.php";
	}

	function getAdminUserUpdate() {
		$user_id = $_GET['user_id'];

		if(empty($user_id)) {
			$_SESSION['message']['alert'] = "Error: invalid parameters";
			$this->redirect($_SERVER['HTTP_REFERER']);
		}

		$action = "/user/postUpdateUser";
		$update = 1;
		$adminUser = $this->dbase->getAdminUser($user_id);
		$userDetails = $adminUser['text'][0];

		$this->getUserRelations($user_id);

		$userRolesId = $this->userRolesId;
		$userGroupsId = $this->userGroupsId;

		$roles = $this->dbase->getAllRoles();
		//$groups = $this->dbase->getAllGroups();

		$organizations = $this->dbase->getOrganizations($_SESSION['userid'], $_SESSION['super_admin']);
		$userOrganizations = $this->dbase->getOrganizations($user_id);
		
		$_SESSION['salt_user_update'] = CryptoLib::generateSalt();

		include __DIR__."/../Views/User/admin_register.php";
	}

	function getAdminDashboard()
	{
		include __DIR__."/../Views/User/admin_dashboard.php";
	}

	function getAccessPolicy()
	{
		$role_id = isset($_GET['role_id']) ? $_GET['role_id'] : 0;
		$this->class = array('User', 'AdminHierarchy', 'Contact', 'Faq', 'Tools', 'Label', 'Language', 'Api','Import', 'Sync', 'Question');
		$roles = $this->dbase->getAllRoles();
		
		if($roles['responseType'] === false)
		{
			echo "Display Centralized error page here with system errors";exit;
			// show error page
		}
		if(isset($_POST['policyData'])){
			$this->dbase->updateAccessPolicy($_POST['policyData'], $_POST['role']);
			$_SESSION['message']['success'] = "Access policy updated successfully";
		}

		$roleAccessPolicy = array();
		if($role_id != 0 && is_numeric($role_id)) {
			$currentPolicy = $this->dbase->getUserSpecificAccessPolicy($role_id);
			if($currentPolicy['responseType'] === '1') {
				$roleAccessPolicy = explode(",", $currentPolicy['text'][0]['accesspolicy']);
			}
		}

		
		$fetched = array();
		foreach($this->class as $value)
		{
		   $absClass = "FES\GEET\ClassLib\\".$value;
		   $obj = new $absClass(false);
		   $all_methods = get_class_methods($obj);
		   $cleaned = array_filter($all_methods, function ($var) { 
			   	switch ($var) {
			   		case stripos($var, 'admin'):
			   			return $var;
			   		case stripos($var, 'get'):
			   			return $var;
		   			case stripos($var, 'post'):
			   			return $var;
			   		case stripos($var, 'delete'):
			   			return $var;
		   			case stripos($var, 'update'):
			   			return $var;
			   	}	
		   });
		   $fetched[$value] = $cleaned;
		   
		}
		include __DIR__."/../Views/User/admin_access_policy.php";
	}

	function getNewAdminUser()
	{
		$action = "/user/postNewUser";
		$roles = $this->dbase->getAllRoles();
		$organizations = $this->dbase->getOrganizations($_SESSION['userid'], $_SESSION['super_admin']);
		$_SESSION['salt_register'] = CryptoLib::generateSalt();
		include __DIR__."/../Views/User/admin_register.php";		
	}

	function postNewUser() {
		if(!empty($_POST)) {

			/*foreach($_POST as $key => $value) {
				if(is_array($value)) continue;
				if(empty($value)){
					$label = ucwords(str_replace('_', ' ', $key));
					$_SESSION['message']['alert'] = $label." cannot be blank";
					$this->redirect($_SERVER['HTTP_REFERER']);
				}
			}

			echo "<pre>";
			print_r($_POST); exit;*/

			if(!isset($_SESSION["salt_register"])){
				$_SESSION['message']['warning'] = "Access Denied: Malicious attempt found.";
				$this->redirect("/#login");
			}

			$email = $_POST['email'];
			$user_id = $_POST['user_id'];

			$result = $this->dbase->checkEmailExists($email);
			if($result['text'][0]['total_rows'] != 0) {
				$_SESSION['message']['alert'] = "Error: provided email address is already registered. - ".$email;
				$this->redirect($_SERVER['HTTP_REFERER']);
			}

			$result = $this->dbase->checkUserIdExists($user_id);
			if($result['text'][0]['total_rows'] != 0) {
				$_SESSION['message']['alert'] = "Error: provided user id is already registered. - ".$user_id;
				$this->redirect($_SERVER['HTTP_REFERER']);
			}

			// decrypt new password
			$password = $this->utils->cryptoJsAesDecrypt($_SESSION["salt_register"], $_POST['password']);
			$confirm_password = $this->utils->cryptoJsAesDecrypt($_SESSION["salt_register"], $_POST['confirm_password']);
			unset($_SESSION["salt_register"]);

			if($password === null){
				if(isset($_SESSION['maliciousAttempt'])){
				 	$_SESSION['maliciousAttempt']++;
					if ($_SESSION['maliciousAttempt'] >= 5){
						$this->dbase->blockUser($data['username'], time());
						$_SESSION['message']['warning'] = "User's account is temporarily blocked due to multiple malicious attempts";
						$this->redirect($_SERVER['HTTP_REFERER']);
					}
				} else {
					$_SESSION['maliciousAttempt'] = 1;
				}
				$_SESSION['message']['warning'] = "Access Denied: Malicious attempt found.";
				$this->redirect($_SERVER['HTTP_REFERER']);	

			}

			if($password !== $confirm_password){
				$_SESSION['message']['alert'] = "Password and Confirm password fields are not matching. No user created. Please register the user again and save.";
				$this->redirect($_SERVER['HTTP_REFERER']);	
				exit;
			}

			// Validate password strength
			$response = $this->utils->checkPasswordStrength($password);
			if($response['type'] !== "1"){
				$_SESSION['message']['alert'] = "Error: ".$response['text'];
				$this->redirect($_SERVER['HTTP_REFERER']);
			}
			if($_POST('medical')){
				$name = $_POST['name'];
				$password = CryptoLib::hash($password, $this->salt);
				//$password = $_POST['password'];
				$city = $_POST['city'];
				$state = $_POST['state'];
				$country = $_POST['country'];
				$medicalSchool = $_POST['medical_school'];
				$degreeCertificate = $_POST['degree_certificate'];
				$yearGraduation = $_POST['year_graduation'];
				$usmleDesignation = $_POST['usmle_designation'];
				$ecfmgCertificate = $_FILES['ecfmg_certificate'];
				$soi = $_POST['soi'];
				$photo = $_FILES['photo'];
				$designation = $_POST['designation'];
				$landline_number = $_POST['landline_number'];
				$mobile_number = $_POST['mobile_number'];
				$address = $_POST['address'];
			}
			if($_POST['resident']){
				$name = $_POST['name'];
				$password = CryptoLib::hash($password, $this->salt);
				//$password = $_POST['password'];
				$city = $_POST['city'];
				$state = $_POST['state'];
				$speciality = $_POST['speciality'];
				$residentProgram = $_POST['resident_program'];
				$npiNumber = $_POST['npi_number'];
				$academicProfile = $_POST['academic_profile'];
				$photo = $_FILES['photo'];
				$service = $_POST['service'];

			}

			$result = $this->dbase->postNewUser($user_id, $name, $designation, $landline_number, $mobile_number, $email, $address, $password);

			if($result['responseType'] === '1') {
				$user_id = $result['text'][0]['user_id'];
				if(!empty($_POST['roles'])) {
					// Prevent admin users who dont have super admin rights to grant roles like super admin, admin, editor, user manager and schemes administrator to any user
					if(!$_SESSION['super_admin']){
						if(($key = array_search(16, $_POST['roles'])) !== false || ($key = array_search(18, $_POST['roles'])) !== false || ($key = array_search(19, $_POST['roles'])) !== false || ($key = array_search(20, $_POST['roles'])) !== false || ($key = array_search(21, $_POST['roles'])) !== false) {
						    unset($_POST['roles'][$key]);
						}
					}
					$result_user_roles = $this->dbase->postNewUserRoles($user_id, $_POST['roles']);
				}

				// if(!empty($_POST['groups'])) {
				// 	$result_user_groups = $this->dbase->postNewUserGroups($user_id, $_POST['groups']);
				// }

				if(!empty($_POST['organizations'])) {
					if($_POST['organizations'] == null || $_POST['organizations'] == 'select' || $_POST['organizations'] == ''){
						$_POST['organizations'] =  $_SESSION['org'];
					}
					$result_user_organizations = $this->dbase->postNewUserOrganization($user_id, $_POST['organizations']);
				} else{
					$result_user_organizations = $this->dbase->postNewUserOrganization($user_id, $_SESSION['org']);
				}

				$_SESSION['message']['success'] = "<strong>".$user_id."</strong> has been successfully created";
				$this->redirect("/User/getAdminUsers");
			} else{
				$_SESSION['message']['alert'] = "Error: failed creating new user.";
				$this->redirect($_SERVER['HTTP_REFERER']);
			}
		} else {
			$_SESSION['message']['alert'] = "Error: post data not avialable";
			$this->redirect($_SERVER['HTTP_REFERER']);
		}
	}

	function postUpdateUser() {
		if(!empty($_POST)) {


			$name = $_POST['name'];
			$user_id = $_POST['user_id'];
			$designation = $_POST['designation'];
			$landline_number = $_POST['landline_number']; 
			$mobile_number = $_POST['mobile_number'];
			$address = $_POST['address'];
			$result = $this->dbase->updateUser($user_id, $name, $designation, $landline_number, $mobile_number, $address);

			if($result['responseType'] === '1') {

				$this->getUserRelations($user_id); //print_r($this->userRolesId); print_r($this->userGroupsId);

				if(!empty($_POST['roles'])) {
					// Prevent admin users who dont have super admin rights to grant roles like super admin, admin, editor, user manager and schemes administrator to any user
					if(!$_SESSION['super_admin']){
						if(($key = array_search(16, $_POST['roles'])) !== false || ($key = array_search(18, $_POST['roles'])) !== false || ($key = array_search(19, $_POST['roles'])) !== false || ($key = array_search(20, $_POST['roles'])) !== false || ($key = array_search(21, $_POST['roles'])) !== false) {
						    unset($_POST['roles'][$key]);
						}
					}
					$this->getNewRelations($_POST['roles'], $this->userRolesId);
					if(sizeof($this->newRelations) > 0) $this->dbase->postNewUserRoles($user_id, $this->newRelations);
					if(sizeof($this->oldRelations) > 0) $this->dbase->deleteUserRoles($user_id, $this->oldRelations);
				} else {
					$this->dbase->deleteUserRoles($user_id);
				}

				if(!empty($_POST['organizations'])) {
					$this->dbase->updateUserOrganization($user_id, $_POST['organizations']);
				}

				$_SESSION['message']['success'] = "<strong>".$user_id."</strong> has been successfully updated";
				$this->redirect("/User/getAdminUsers");
			} else{
				$_SESSION['message']['alert'] = "Error: failed updating user. - ".$result['responseType'];
				$this->redirect($_SERVER['HTTP_REFERER']);
			}
		} else {
			$_SESSION['message']['alert'] = "Error: post data not available";
			$this->redirect($_SERVER['HTTP_REFERER']);
		}
        //echo '<pre>'; print_r($_SESSION); exit;
	}

	

	function getNewRelations($updatedRelations, $currentRelations) {
		$this->newRelations = $this->oldRelations = array();
		foreach($updatedRelations as $rel) {
			if(!in_array($rel, $currentRelations)) $this->newRelations[] = $rel;
		}

		foreach($currentRelations as $rel) {
			if(!in_array($rel, $updatedRelations)) $this->oldRelations[] = $rel;
		}
	}

	 /** 
     * Check duplicate email address for provided user details.
     * User details are provided from user registration form in the system.
     */
	function getUserEmail() {
		$email = $_GET['email'];
		if(!empty($email)) {
			$result = $this->dbase->checkEmailExists($email);
			if($result['text'][0]['total_rows'] != 0) {
				$response = array(
					'status' => true,
					'msg' => "<strong>".$email."</strong> is already registered. Please provide different email address"
				);
			} else {
				$response = array(
					'status' => false
				);
			}

		} else {
			$response = array(
				'status' => false,
				'msg' => 'Error! invlid paramaters provided'
			);
		}
		die(json_encode($response));
	}
    
    /** 
     * Check duplicate user id for provided user details.
     * User details are provided from user registration form in the system.
     */
	function getUserId() {
		$user_id = $_GET['id'];
		if(!empty($user_id)) {
			$result = $this->dbase->checkUserIdExists($user_id);
			if($result['text'][0]['total_rows'] != 0) {
				$response = array(
					'status' => true,
					'msg' => "<strong>".$user_id."</strong> is already registered. Please provide different user id"
				);
			} else {
				$response = array(
					'status' => false
				);
			}

		} else {
			$response = array(
				'status' => false,
				'msg' => 'Error! invlid paramaters provided'
			);
		}
		die(json_encode($response));
	}

	// Roles management
	function getRoles() {
		$fields = $this->dbase->getAllRoles();

		include __DIR__."/../Views/User/admin_roles.php";
	}

	function getNewRole() {
		$action = "/User/postNewRole";
		include __DIR__."/../Views/User/new_role.php";
	}

	function updateRole() {
		$role_id = $_GET['role_id'];
		
		if(empty($role_id)) {
			$_SESSION['message']['alert'] = "Error: invalid parameters";
			$this->redirect($_SERVER['HTTP_REFERER']);
		}

		$update = 1;
		$role = $this->dbase->getRoleByID($role_id);
		$roleDetails = $role['text'][0];

		$action = "/User/postUpdateRole";
		include __DIR__."/../Views/User/new_role.php";
	}

	function postNewRole() {
		if(!empty($_POST)) {
			$role_name = $_POST['name'];
			$role_desc = $_POST['description'];

			$result = $this->dbase->postNewRole($role_name, $role_desc);
			if($result['responseType'] === '1') {
				$_SESSION['message']['success'] = "<strong>".$role_name."</strong> has been successfully created";
				$this->redirect("/User/getRoles");
			} else {
				$_SESSION['message']['alert'] = "Error: failed creating new role. - ".$result['responseType'];
				$this->redirect($_SERVER['HTTP_REFERER']);
			}

		} else {
			$_SESSION['message']['alert'] = "Error: post data not available";
			$this->redirect($_SERVER['HTTP_REFERER']);
		}
	}

	function postUpdateRole() {
		if(!empty($_POST)) {
			$role_id = $_POST['role_id'];
			$role_name = $_POST['name'];
			$role_desc = $_POST['description'];

			$result = $this->dbase->updateRole($role_id, $role_desc);
			if($result['responseType'] === '1') {
				$_SESSION['message']['success'] = "<strong>".$role_name."</strong> has been successfully Updated";
				$this->redirect("/User/getRoles");
			} else {
				$_SESSION['message']['alert'] = "Error: failed updating new role. - ".$result['responseType'];
				$this->redirect($_SERVER['HTTP_REFERER']);
			}

		} else {
			$_SESSION['message']['alert'] = "Error: post data not available";
			$this->redirect($_SERVER['HTTP_REFERER']);
		}
	}

	// Groups Management
	// function getGroups() {
	// 	$fields = $this->dbase->getAllGroups();

	// 	include __DIR__."/../Views/User/admin_groups.php";
	// }

	// Reset password
	function getResetPassword() {
		$user_id = $_GET['user_id'];
		
		if(empty($user_id)) {
			$_SESSION['message']['alert'] = "Error: invalid parameters";
			$this->redirect($_SERVER['HTTP_REFERER']);
		}

		$adminUser = $this->dbase->getAdminUser($user_id);
		$userDetails = $adminUser['text'][0];

		$builder = new CaptchaBuilder(5);
		$builder->build();
		$_SESSION['captchaValue'] = $builder->getPhrase();

		$_SESSION['salt_reset'] = CryptoLib::generateSalt();

		include __DIR__."/../Views/User/reset_password.php";
	}

	function postResetPassword() {
		if(!empty($_POST)){

			if(!isset($_POST['loginCaptcha'])){
				$_SESSION['message']['warning'] = "Error: Missing captcha value.";
				$this->redirect($_SERVER['HTTP_REFERER']);
				exit;
			}
			if(strtolower($_POST['loginCaptcha']) !== $_SESSION['captchaValue']){
				$_SESSION['message']['warning'] = "Error: Incorrect captcha value";
				$this->redirect($_SERVER['HTTP_REFERER']);
			}

			// decrypt new password
			if(!isset($_SESSION["salt_reset"])){
				$_SESSION['message']['warning'] = "Access Denied: Malicious attempt found.";
				$this->redirect("/");	
			}
			$password = $this->utils->cryptoJsAesDecrypt($_SESSION["salt_reset"], $_POST['password']);
			$confirm_password = $this->utils->cryptoJsAesDecrypt($_SESSION["salt_reset"], $_POST['confirm_password']);

			unset($_SESSION["salt_reset"]);
			if($password === null){
				if(isset($_SESSION['maliciousAttempt'])){
				 	$_SESSION['maliciousAttempt']++;
					if ($_SESSION['maliciousAttempt'] >= 5){
						$this->dbase->blockUser($_SESSION['userid'], time());
						$_SESSION['message']['warning'] = "User's account is temporarily blocked due to multiple malicious attempts";
						$this->redirect($_SERVER['HTTP_REFERER']);
					}
				} else {
					$_SESSION['maliciousAttempt'] = 1;
				}
				$_SESSION['message']['warning'] = "Access Denied: Malicious attempt found.";
				$this->redirect($_SERVER['HTTP_REFERER']);	

			}

			if($password !== $confirm_password){
				$_SESSION['message']['alert'] = "Password and confirm password fields are not matching. Password was not updated.";
				$this->redirect($_SERVER['HTTP_REFERER']);	
			}

			$adminUser = $this->dbase->getAdminUser($_POST['user_id']);
            $userDetails = $adminUser['text'][0];

            $hashPassword = CryptoLib::hash($password, $this->salt);

            // Check whether new password is similar to older passwords or not
            $checkHistory = $this->dbase->checkPasswordHistory($_POST['user_id']);
            if($checkHistory['responseType'] !== "-1"){
            	foreach ($checkHistory['text'] as $row){
            		if($row['password'] === $hashPassword){
            			 $_SESSION['message']['alert'] = "Error: New password should not be similar to your last three passwords.";
               			 $this->redirect($_SERVER['HTTP_REFERER']);
            		}
            	}
            } else{
            	$_SESSION['message']['alert'] = "Error: There is some problem serving your request. Please contact site administrator";
				$this->redirect($_SERVER['HTTP_REFERER']);
            }

            if($userDetails['password'] === $hashPassword) {
                $_SESSION['message']['alert'] = "Error: New password and current password can not be same";
                $this->redirect($_SERVER['HTTP_REFERER']);
            }

			// Validate password strength
			$response = $this->utils->checkPasswordStrength($password);
			if($response['type'] !== "1"){
				$_SESSION['message']['alert'] = $response['text'];
				$this->redirect($_SERVER['HTTP_REFERER']);
			}

			$user_id = $_POST['user_id'];
			//$password = $_POST['password'];

			$result = $this->dbase->resetPassword($user_id, $hashPassword);
			if($result['responseType'] === '1') {
				$this->dbase->addPasswordHistory($user_id, $hashPassword, $_SESSION['userid']);
				$_SESSION['message']['success'] = "Password has been successfully reset for <strong>".$user_id."</strong>";
				$this->redirect("/User/getAdminUsers");
			} else {
				$_SESSION['message']['alert'] = "Error: failed to reset password. - ".$result['responseType'];
				$this->redirect($_SERVER['HTTP_REFERER']);
			}
		} else {
			$_SESSION['message']['alert'] = "Error: post data not avialable";
			$this->redirect($_SERVER['HTTP_REFERER']);
		}
	}
    
    // Change password
	function getChangePassword() {
		$adminUser = $this->dbase->getAdminUser($_SESSION['userid']);
		$userDetails = $adminUser['text'][0];

		$builder = new CaptchaBuilder(5);
		$builder->build();
		$_SESSION['captchaValue'] = $builder->getPhrase();
		$_SESSION['salt_change'] = CryptoLib::generateSalt();
		include __DIR__."/../Views/User/change_password.php";
	}
    
    function postChangePassword() {
		if(!empty($_POST)){

			if(!isset($_POST['loginCaptcha'])){
				$_SESSION['message']['warning'] = "Error: Missing captcha value.";
				$this->redirect($_SERVER['HTTP_REFERER']);
				exit;
			}
			if(strtolower($_POST['loginCaptcha']) !== $_SESSION['captchaValue']){
				$_SESSION['message']['warning'] = "Error: Incorrect captcha value";
				$this->redirect($_SERVER['HTTP_REFERER']);
			}

			$user_id = $_SESSION['userid'];

			if(!isset($_SESSION["salt_change"])){
				$_SESSION['message']['warning'] = "Access Denied: Malicious attempt found.";
				$this->redirect("/");	
			}
			// decrypt current password
			$current_password = $this->utils->cryptoJsAesDecrypt($_SESSION["salt_change"], $_POST['current_password']);
			if($current_password === null){
				if(isset($_SESSION['maliciousAttempt'])){
				 	$_SESSION['maliciousAttempt']++;
					if ($_SESSION['maliciousAttempt'] >= 5){
						$this->dbase->blockUser($data['username'], time());
						$_SESSION['message']['warning'] = "Your account is temporarily blocked due to multiple malicious attempts";
						$this->redirect($_SERVER['HTTP_REFERER']);
					}
				} else {
					$_SESSION['maliciousAttempt'] = 1;
				}
				$_SESSION['message']['warning'] = "Access Denied: Malicious attempt found.";
				$this->redirect($_SERVER['HTTP_REFERER']);	

			}

			// decrypt new password
			$password = $this->utils->cryptoJsAesDecrypt($_SESSION["salt_change"], $_POST['password']);
			$confirm_password = $this->utils->cryptoJsAesDecrypt($_SESSION["salt_change"], $_POST['confirm_password']);
			unset($_SESSION["salt_change"]);
			if($password === null){
				if(isset($_SESSION['maliciousAttempt'])){
				 	$_SESSION['maliciousAttempt']++;
					if ($_SESSION['maliciousAttempt'] >= 5){
						$this->dbase->blockUser($data['username'], time());
						$_SESSION['message']['warning'] = "Your account is temporarily blocked due to multiple malicious login attempts";
						$this->redirect("/");
					}
				} else {
					$_SESSION['maliciousAttempt'] = 1;
				}
				$_SESSION['message']['warning'] = "Access Denied: Malicious attempt found.";
				$this->redirect("/#login");	

			}

			if($password !== $confirm_password){
				$_SESSION['message']['alert'] = "Password and confirm password fields are not matching. Password was not updated.";
				$this->redirect($_SERVER['HTTP_REFERER']);	
			}
            
            $adminUser = $this->dbase->getAdminUser($user_id);
            $userDetails = $adminUser['text'][0];
			
            if($userDetails['password'] != CryptoLib::hash($current_password, $this->salt)) {
                $_SESSION['message']['alert'] = "Error: current password doesn't match";
                $this->redirect($_SERVER['HTTP_REFERER']);
            }

            $hashedPassword = CryptoLib::hash($password, $this->salt);

           	// Check whether new password is similar to older passwords or not
            $checkHistory = $this->dbase->checkPasswordHistory($user_id);
            if($checkHistory['responseType'] !== "-1"){
            	foreach ($checkHistory['text'] as $row){
            		if($row['password'] === $hashedPassword){
            			 $_SESSION['message']['alert'] = "Error: New password should not be similar to your last three passwords.";
               			 $this->redirect($_SERVER['HTTP_REFERER']);
            		}
            	}
            } else{
            	$_SESSION['message']['alert'] = "Error: There is some problem serving your request. Please contact site administrator";
				$this->redirect($_SERVER['HTTP_REFERER']);
            }

            if($userDetails['password'] === $hashedPassword) {
                $_SESSION['message']['alert'] = "Error: New password and current password can not be same";
                $this->redirect($_SERVER['HTTP_REFERER']);
            }

            // Validate password strength
			$response = $this->utils->checkPasswordStrength($password);
			if($response['type'] !== "1"){
				$_SESSION['message']['alert'] = $response['text'];
				$this->redirect($_SERVER['HTTP_REFERER']);
			}

			//$password = $_POST['password'];

			$result = $this->dbase->resetPassword($user_id, $hashedPassword);
			if($result['responseType'] === '1') {
				$this->dbase->addPasswordHistory($user_id, $hashedPassword, $user_id);
				$_SESSION['message']['success'] = "Password has been successfully changed for <strong>".$user_id."</strong>";
				$this->redirect("/User/getAdminUsers");
			} else {
				$_SESSION['message']['alert'] = "Error: failed to change password. - ".$result['responseType'];
				$this->redirect($_SERVER['HTTP_REFERER']);
			}
		} else {
			$_SESSION['message']['alert'] = "Error: post data not avialable";
			$this->redirect($_SERVER['HTTP_REFERER']);
		}
	}


	// Send Report Email
	function sendReport() {
		$userId = $_GET['user_id'];
		if(empty($userId)) {
			die('Please provide user to send a report');
		}

		$adminUser = $this->dbase->getAdminUser($userId);
		$userDetails = $adminUser['text'][0];

		$this->mail->from_name = 'GEET';
		$this->mail->from_email = 'mitesh@fes.org.in';

		$emailData = array(
			'subject' => 'GEET Report ('.date('d F, Y').')',
			'email' => $userDetails['email'],
			'fullname' => $userDetails['name'],
			'site_url' => $GLOBALS['base_url'],
			'support_email' => 'support@fes.org.in',
			'site_represent_title' => 'GEET: GIS Enabled Entitlement Tracking System',
			'report_image' => "http://ifmt.local/Reports/maritalStatusReports?code=912000000000000000"
		);
		if($this->mail->sendMail('report', $emailData)) {
			$_SESSION['message']['success'] = "Report email has been successfully sent to <strong>".$userDetails['email']."</strong>";
			$this->redirect("/User/getAdminUsers");
		} else {
			$_SESSION['message']['alert'] = "Error: failed to send report.";
			$this->redirect("/User/getAdminUsers");
		}
	}
    
    function array_flatten($array = null) {
        $result = array();

        if (!is_array($array)) {
            $array = func_get_args();
        }

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, $this->array_flatten($value));
            } else {
                $result = array_merge($result, array($key => $value));
            }
        }

        return $result;
    }
    
    // Default region selection
   
    function getPageCategories(){
    	$categories = $this->dbase->getPageCategories();
		include __DIR__."/../Views/User/getPageCategories.php";
    }

    function getPageCategory(){
    	if(isset($_POST['c_id']) && $_POST['c_id'] != ""){
    		$pageCategory = $this->dbase->getPageCategory($_POST['c_id']);
			include __DIR__."/../Views/User/getPageCategory.php";	
    	} else{
 			$_SESSION['message']['alert'] = "Error: missing parameter(s).";
			$this->redirect($_SERVER['HTTP_REFERER']);   		
    	}
    }

    function postUpdatePageCategory(){
    	if(isset($_POST['c_id']) && isset($_POST['cname']) && isset($_POST['weight'])){
    		$response = $this->dbase->updatePageCategory($_POST);
    		if($response['responseType'] === '1'){
    			$_SESSION['message']['success'] = "Category - <b>".$_POST['cname']."</b> updated successfully";
				$this->redirect("/User/getPageCategories");
    		} else{
    			$_SESSION['message']['alert'] = "Coudn't update category.";
				$this->redirect($_SERVER['HTTP_REFERER']);
    		}
    	} else{
 			$_SESSION['message']['alert'] = "Error: missing parameter(s).";
			$this->redirect($_SERVER['HTTP_REFERER']);   		
    	}
    }

    function getNewCategory(){
    	include __DIR__."/../Views/User/getNewCategory.php";
    }

    function postPageCategory(){
    	if(isset($_POST['cname']) && isset($_POST['weight'])){
    		$response = $this->dbase->postPageCategory($_POST);
    		if($response['responseType'] === '1'){
    			$_SESSION['message']['success'] = "Category - <b>".$_POST['cname']."</b> updated successfully";
				$this->redirect("/User/getPageCategories");
    		} else{
    			$_SESSION['message']['alert'] = "Coudn't create category.";
				$this->redirect($_SERVER['HTTP_REFERER']);
    		}
    	} else{
 			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);   		
    	}
    }

    function getAllPages(){
    	$pages = $this->dbase->getAllMenuPages();
    	include __DIR__."/../Views/User/getAllPages.php";
	}

	function getPage(){
		if(isset($_POST['page_id']) && $_POST['page_id'] != ""){
    		$page = $this->dbase->getMenuPage($_POST['page_id']);
    		$pageCategories = $this->dbase->getPageCategories();
    		if($page['responseType'] === '1'){
    			include __DIR__."/../Views/User/getPage.php";
    		} else{
    			$_SESSION['message']['alert'] = "Error: Page doesn't exist.";
				$this->redirect($_SERVER['HTTP_REFERER']);   			
    		}
			
    	} else{
 			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);   		
    	}
	}

	function postUpdatePage(){
		if(isset($_POST['pid']) && $_POST['pid'] != "" && isset($_POST['pname']) && isset($_POST['plink']) && isset($_POST['category']) && isset($_POST['for_mobile'])){
			$response = $this->dbase->updateMenuPage($_POST);
    		if($response['responseType'] === '1'){
    			$_SESSION['message']['success'] = "Page - <b>".$_POST['pname']."</b> updated successfully";
				$this->redirect("/User/getAllPages");
    		} else{
    			$_SESSION['message']['alert'] = "Coudn't update page.";
				$this->redirect($_SERVER['HTTP_REFERER']);
    		}
		} else{
			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);   			
		}
	}

	function getNewPage(){
		$pageCategories = $this->dbase->getPageCategories();
		include __DIR__."/../Views/User/getNewPage.php";
	}

	function postPage(){
    	if(isset($_POST['pname']) && isset($_POST['plink']) && isset($_POST['category']) && isset($_POST['for_mobile'])){
    		$response = $this->dbase->postPage($_POST);
    		if($response['responseType'] === '1'){
    			$_SESSION['message']['success'] = "Page - <b>".$_POST['pname']."</b> updated successfully";
				$this->redirect("/User/getAllPages");
    		} else{
    			$_SESSION['message']['alert'] = "Coudn't create page.";
				$this->redirect($_SERVER['HTTP_REFERER']);
    		}
    	} else{
 			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);   		
    	}
    }

    function getAllOrganizations(){
    	$organizations = $this->dbase->getOrganizations($_SESSION['userid'], true);
    	include __DIR__."/../Views/User/getAllOrganizations.php";
	}

	function getOrganization(){
		if(isset($_GET['id']) && $_GET['id'] != ""){
    		$organization = $this->dbase->getOrganizationUpdate($_GET['id']);
    		if($organization['responseType'] === '1'){
    			include __DIR__."/../Views/User/getOrganization.php";
    		} else{
    			$_SESSION['message']['alert'] = "Error: Page doesn't exist.";
				$this->redirect($_SERVER['HTTP_REFERER']);   			
    		}
			
    	} else{
 			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);   		
    	}
	}

	function postUpdateOrganization(){
		if(isset($_POST['id']) && $_POST['id'] != "" && isset($_POST['name']) && $_POST['name'] != ""){
    		$response = $this->dbase->postUpdateOrganization($_POST['id'], $_POST['name']);
    		if($response['responseType'] === '1'){
    			$_SESSION['message']['success'] = "organization - <b>".$_POST['name']."</b> updated successfully";
				$this->redirect("/User/getAllOrganizations");
    		} else{
    			$_SESSION['message']['alert'] = "Coudn't update organization.";
				$this->redirect($_SERVER['HTTP_REFERER']);
    		}
			
    	} else{
 			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);   		
    	}	
	}

	function getNewOrganization(){
		include __DIR__."/../Views/User/getNewOrganization.php";
	}

	function postNewOrganization(){
		if(isset($_POST['name']) && $_POST['name'] != ""){
    		$response = $this->dbase->postNewOrganization($_POST['name']);
    		if($response['responseType'] === '1'){
    			$_SESSION['message']['success'] = "organization - <b>".$_POST['name']."</b> added successfully";
				$this->redirect("/User/getAllOrganizations");
    		} else{
    			$_SESSION['message']['alert'] = "Coudn't add organization.";
				$this->redirect($_SERVER['HTTP_REFERER']);
    		}
			
    	} else{
 			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);   		
    	}	
	}

}