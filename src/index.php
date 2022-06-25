<?php
namespace FES\GEET\ClassLib;
require "../vendor/autoload.php";

if(isset($_GET['debug']) && @$_GET['debug'] === "1"){
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
} else{
	ini_set('display_errors', 0);
	error_reporting(E_ERROR | E_PARSE);
}

if(session_status()){
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

if(isset($_SERVER['HTTPS'])){
	$protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
}
else{
	if(strpos($_SERVER['HTTP_HOST'], 'local') !== false){
		$protocol = 'http';
	} else{
		$protocol = 'https';	
	}
}

$GLOBALS['base_url'] = $protocol."://".$_SERVER['HTTP_HOST']."/";

$uri = explode('/', $_SERVER['REQUEST_URI']);
array_shift($uri);

//var_dump($uri[0]); exit;
if(empty(array_filter($uri)))
{
	$class = "GeetMain";
	$action = "home";
}
else
{
	$class = ucfirst($uri[0]);
	if(strpos($uri[1], "?") === false )
	{
		$action = $uri[1];
	}else{
		$action = substr($uri[1], 0, strpos($uri[1], "?"));
		$params = substr($uri[1], strpos($uri[1], "?"));
	}
}

// Security bypass rules for mobile APIs
$mobileAPIs = array('getLoginMobile', 'getLogoutMobile', 'getSchemeSearch', 'getAccessPolicyMobile', 'getSyncTable', 'postSyncTable', 'getAppUpdateInfoEnumerator', 'getAppUpdateInfoPublic', 'getAppUpdateInfoMonitor', 'getLatestVersionEnumerator', 'getLatestVersionPublic', 'getLatestVersionMonitor', 'postEligibilityCheckCounter');

$_SESSION['appCall'] = false;

if(in_array($action, $mobileAPIs)){
		$_SESSION['appCall'] = true;
}

if(!$_SESSION['appCall']){
	include_once '../vendor/owasp/csrf-protector-php/libs/csrf/csrfprotector.php';

	//Initialise CSRFGuard library
	\csrfProtector::init();
}

if(!isset($_SESSION['language'])){
	//set default language
	$_SESSION['language']['current_id'] = 1; // 1 is english
	$_SESSION['language']['id_default'] = 1; // 1 is english
}

include __DIR__."/./locale/lang_1.php";
if(isset($_SESSION['language']['current_id']) && file_exists(__DIR__."/./locale/lang_".$_SESSION['language']['current_id'].'.php')) {
    include  __DIR__."/./locale/lang_".$_SESSION['language']['current_id'].'.php';
}

if(file_exists(__DIR__."/./ClassLib/".$class.'.php')){
	include  __DIR__."/./ClassLib/".$class.'.php';
} else{
	$_SESSION['message']['warning'] = (isset($_SERVER['HTTPS']) ? "https://" : "http://").$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']." is not found.";
	header("location: /");
}
// $obj = new Scheme;

$_SESSION['class'] = $class;
$_SESSION['method'] = $action;

$class = '\\'. __NAMESPACE__ . '\\' . $class;
$obj = new $class();
$obj->$action();