<?php

namespace FES\GEET\ClassLib;
use FES\GEET\ClassLib\Action;
use FES\GEET\ClassLib\ClassDBase\UserDBase;
use FES\GEET\ClassLib\ClassDBase\UtilsDBase;
use Gregwar\Captcha\CaptchaBuilder;
use IcyApril\CryptoLib;
/**
* Main Class
*/
class GeetMain
{
	private $primaryAction;
	private $data;
	protected $path;
	private $user_dbase;
	private $utils_dbase;

	function __construct()
	{
		$this->primaryAction = new Action;
		$this->user_dbase = new UserDBase;
		$this->utils_dbase = new UtilsDBase;
		$this->__default();
	}

	function __addAction($data)
	{
		foreach ($data as $key => $value) {
			$this->primaryAction->register($value);
		}	
	}

	private function __default()
	{
	    if(!$_SESSION['appCall']){
    		$this->__sessionLifeTime();
    		$this->__requestValidation();
    	}
		$this->__access();
		$this->__language();
		if(isset($_POST)){
			$_POST = $this->utils_dbase->validateGETPOST($_POST);
		} elseif(isset($_GET)){
			$_GET = $this->utils_dbase->validateGETPOST($_GET);
		} else{}
		
		if(empty($_SESSION['message']) || (!is_array($_SESSION['message']) && !is_object($_SESSION['message']))){
			$_SESSION['message'] = array('success' => '', 'alert' => '', 'warning' => '');
		}
	}

	private function __sessionLifeTime(){
		$time = $_SERVER['REQUEST_TIME'];

		/**
		*
		* Defining session timeout
		* 15 minute timeout, specified in seconds
		*
		*/
		$timeout_duration = 900; //15 minutes

		/**
		* Check if user's last activity time exceeds session's time out limit and delete if found delete previous $_SESSION data and start a new one.
		*/
		if (isset($_SESSION['admin']) && isset($_SESSION['LAST_ACTIVITY']) && ($time - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {

			$userid = $_SESSION['userid'];

			$this->destroySession();
			$this->addAutoLogoutEntry($userid);
			$_SESSION['message']['warning'] = "Due to inactivity, you need to login again";
			$this->redirect("/#login");
		}

		$_SESSION['LAST_ACTIVITY'] = $time;
	}

	private function __requestValidation(){
		if(isset($_SESSION['admin'])){
			if(isset($_COOKIE['JAP'])){
				if(in_array($_COOKIE['JAP'], $_SESSION['secretKey']) === false || $_SESSION['clientIP'] !== $_SERVER['REMOTE_ADDR']){
					$userid = $_SESSION['userid'];
					$this->destroySession();
					$this->addAutoLogoutEntry($userid, 'Auto Logout due to request validation failure');
					$_SESSION['message']['warning'] = "Request validation failed, you need to login again";
					$this->redirect("/#login");
					//header('HTTP/1.0 403 Forbidden');
                    //exit("<h2>403 Access Forbidden</h2>");
				} else{
					$key = CryptoLib::randomString(127);
					array_push($_SESSION['secretKey'], $key);
					setcookie('JAP', $key, 0, '/', '', false, true);
				}
			} else{
				$userid = $_SESSION['userid'];
				$this->destroySession();
				$this->addAutoLogoutEntry($userid, 'Auto Logout due to request validation failure');
				$_SESSION['message']['warning'] = "Request validation failed, you need to login again";
				$this->redirect("/#login");
				/*header('HTTP/1.0 403 Forbidden');
                exit("<h2>403 Access Forbidden</h2>");*/
				// Cookie is not available
				/*$this->destroySession();
				$_SESSION['message']['warning'] = '<b>Access denied:</b>Cookie not set. You are not authorised to access '.$_SERVER['REQUEST_URI'];
				$this->home(true);*/
			}
		}
	}

	private function __access()
	{
		$uri = explode('/', $_SERVER['REQUEST_URI']);
		$classAndAction = $_SESSION['class'].".".$_SESSION['method'];
		if(!isset($_SESSION['admin'])){
			$policies = $this->user_dbase->getUserSpecificAccessPolicy("1"); // 1 is for public users
			if($policies['responseType'] == 1){
				$policyArray = array();
				foreach($policies['text'] as $row){
					array_push($policyArray, $row['accesspolicy']);
				}
				$_SESSION['access_policy'] = explode(",",implode(",",$policyArray)); 
				if(!in_array($classAndAction, $_SESSION['access_policy']) && $classAndAction !== "."  && $classAndAction !== "GeetMain.home" && !isset($_REQUEST['mobile'])){
					//$uri = explode('/', $_SERVER['REQUEST_URI']);
					$classAndAction = $_SESSION['class'].".".$_SESSION['method'];
					$_SESSION['message']['warning'] = '<b>Access denied:</b> You are not authorised to acces '.$_SERVER['REQUEST_URI'];
					$this->home(true);
				}				
			}
		} else{
			if($_SESSION['sessionID'] !== session_id() || $_SESSION['clientIP'] !== $_SERVER['REMOTE_ADDR']){
				$this->destroySession();
				$_SESSION['message']['warning'] = '<b>Access denied:</b> You are not authorised to acces '.$_SERVER['REQUEST_URI'];
				$this->redirect("/#login");
				exit;
			}
			$roles = $this->user_dbase->getRole($_SESSION['userid']);
			if($roles['responseType'] == 1)
			{
				$roleIds = array();
				foreach($roles['text'] as $rowElement){
					array_push($roleIds,$rowElement['role_id']);
				}
			}
			$allRoles = implode(",",$roleIds);
			$policies = $this->user_dbase->getUserSpecificAccessPolicy($allRoles);
			if($policies['responseType'] == 1)
			{
				$policyArray = array();
				foreach($policies['text'] as $row){
					array_push($policyArray, $row['accesspolicy']);
				}
				$_SESSION['access_policy'] = explode(",",implode(",",$policyArray));

				//Dedined access if requested action of class is absent in $_SESSION['access_policy']
				if(!in_array($classAndAction, $_SESSION['access_policy']) && $classAndAction != '.' && $classAndAction !== "GeetMain.home"){

					$this->user_dbase->addUserPerformedActions($_SESSION['userid'], $_SERVER['REQUEST_URI'], 'Failed - Access denied', json_encode($_REQUEST), $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);

					$_SESSION['message']['warning'] = '<b>Access denied:</b> You are not authorised to acces '.$_SERVER['REQUEST_URI'];
					$this->home(true);
				}

				$parametersSubmitted = json_encode($_REQUEST);
				$this->user_dbase->addUserPerformedActions($_SESSION['userid'], $_SERVER['REQUEST_URI'], 'Success', $parametersSubmitted, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);

				// Get page links based on page access for admin panel 
				$pagePolicies = $this->user_dbase->getAllPages();
				if($pagePolicies['responseType'] == 1){
					$pageAccess = array();
					$j=0;
					foreach($pagePolicies['text'] as $category){
						$page_id = explode(" delimit ", $category['page_id']);
						$page_name = explode(" delimit ", $category['page_name']);
						$page_link = explode(" delimit ", $category['page_link']);
						$pageLinkTemp = $page_link;
						$pages = array();
						$k=0;
						for($i=0;$i<sizeof($page_id);$i++){
							$temp = explode("/", $page_link[$i]);
							if(in_array($temp[1].".".$temp[2], $_SESSION['access_policy'])){
								$pages['page_id'][$k] = $page_id[$i];
								$pages['page_name'][$k] = $page_name[$i];
								$pages['page_link'][$k] = $page_link[$i];
								$k++;
							}
						}
						if(isset($pages['page_id'])){
							if(sizeof($pages['page_id'])>0){
								$pageAccess[$j]['page_id'] = implode(" delimit ", $pages['page_id']);
								$pageAccess[$j]['page_name'] = implode(" delimit ", $pages['page_name']);
								$pageAccess[$j]['page_link'] = implode(" delimit ", $pages['page_link']);
								$pageAccess[$j]['page_category_id'] = $category['page_category_id'];
								$pageAccess[$j]['page_category_name'] = $category['page_category_name'];
								$j++;
							}
						}
					}
					$_SESSION['page_policy'] = $pageAccess;
				}
			} else{
				$_SESSION['message']['warning'] = 'Error: Coudnt fetch access information.';
			}
		}
	}
	
	function __language(){
		$_SESSION['language']['id'] = $_SESSION['language']['name'] = array();
		$languages = $this->utils_dbase->getLanguageList();
		if($languages['responseType'] === '1'){
			foreach($languages['text'] as $lang){
				array_push($_SESSION['language']['id'], $lang['id']);
				array_push($_SESSION['language']['name'], $lang['name']);
			}
		}
	}

	function home($var = false)
	{	
		if($var)
		{
			if(isset($_SESSION['admin'])){
				include __DIR__."/../Views/Home/welcome.php";
			} else{
				header("Location: /");
			}
			exit;
		}else{
			if(isset($_SESSION['admin'])){
				$this->redirect("/Tools/getTools");
			} else{
				$_SESSION['salt'] = CryptoLib::generateSalt();
				if($_SESSION['class'] != "User" && $_SESSION['method'] != "getLogin"){
					$builder = new CaptchaBuilder(5);
					$builder->build();
					$_SESSION['captchaValue'] = $builder->getPhrase();
				}

				include __DIR__."/../Views/Home/welcome.php";
			}
		}
	}
	
	function getClassAndAction($uri){
		array_shift($uri);
		//var_dump($_SERVER['REQUEST_URI']);
		$class = ucfirst($uri[0]);
		$action = "";
		if(!empty(array_filter($uri))){
			if(strpos($uri[1], "?") === false){
				$action = $uri[1];
			} else{
				$action = substr($uri[1], 0, strpos($uri[1], "?"));
			}
		}
		return $class.".".$action;
	}
	
	function getPDF(){
		var_dump($_POST); exit;
		if(isset($_POST['echtml'])){
			$this->pdf->stream($_POST['echtml']);
		}
	}

	function redirect($path)
	{	
		header("Location: $path");
		exit;
	}

	function addAutoLogoutEntry($userid, $msg = 'Auto Logout due to session timeout'){
		$this->user_dbase->addWebSession($userid, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'], $msg, 'Success');
	}

	function destroySession(){
		
		session_regenerate_id();

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

		unset($_COOKIE['JAP']);

		//Start a new session
	    session_start();
		setcookie(
		    'GTSESSID',//name  
		    session_id(),//value  
		    0,//expires at end of session  
		    '/geet',//path  
		    $_SERVER['HTTP_HOST'],//domain  
		    true, //secure  
		    true //httpOnly
		);
	}
}