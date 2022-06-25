<?php
namespace FES\GEET\ClassLib;

use FES\GEET\ClassLib\ClassDBase\UtilsDBase;
use Gregwar\Captcha\CaptchaBuilder;

/**
* Utils class
*/
class Utils{
	
	function __construct()
	{
		$this->dbase = new UtilsDBase;
	}
	
	function changeLanguage(){
		if(isset($_POST['lang'])){
			$_SESSION['language']['current_id'] = $_POST['lang'];
			header('Location: '.$_SERVER['HTTP_REFERER']);
		}
	}

	function refreshCaptcha(){
		$builder = new CaptchaBuilder(5);
		$builder->build();
		$_SESSION['captchaValue'] = $builder->getPhrase();
		$response['type'] = "1";
		$response['text'] = $builder->inline();
		echo json_encode($response);
	}


	function validatePasswordStrength(){
		if(isset($_POST['passwd']) && isset($_POST['page'])){
			if($_POST['page'] === 'passchange'){
				$salt = $_SESSION["salt_change"];
			} elseif($_POST['page'] === 'passreset'){
				$salt = $_SESSION["salt_reset"];
			} elseif($_POST['page'] === 'register'){
				$salt = $_SESSION["salt_register"];
			} else{
				$response['type'] = "-1";
				$response['text'] = "Invalid page information";	
				echo json_encode($response);
				exit;
			}
			$response = $this->checkPasswordStrength($this->cryptoJsAesDecrypt($salt, $_POST['passwd']));
		} else{
			$response['type'] = "-1";
			$response['text'] = "Missing parameters";
		}
		echo json_encode($response);
	}

	function checkPasswordStrength($password) {
		// Validate password strength
		$uppercase = preg_match('@[A-Z]@', $password);
		$lowercase = preg_match('@[a-z]@', $password);
		$number    = preg_match('@[0-9]@', $password);
		$specialChars = preg_match('@[^\w]@', $password);

		if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
		   	$response['type'] = "2";
			$response['text'] = "Password Strength: <b>Weak</b><br>Password should be, <ul><li>At least 8 characters in length</li><li>Should include at least one upper case letter, one number, and one special character.</li></ul>";
		}else{
		    $response['type'] = "1";
			$response['text'] = "Password Strength: <b>Strong</b>";
		}
		return $response;
	}

	/**
	* Decrypt data from a CryptoJS json encoding string
	*
	* @param mixed $passphrase
	* @param mixed $jsonString
	* @return mixed
	*/
	function cryptoJsAesDecrypt($passphrase, $jsonString){
	    $jsondata = json_decode($jsonString, true);
	    try {
	        $salt = hex2bin($jsondata["s"]);
	        $iv  = hex2bin($jsondata["iv"]);
	    } catch(Exception $e) { 
	    	return null; 
	    }
	    $ct = base64_decode($jsondata["ct"]);
	    $concatedPassphrase = $passphrase.$salt;
	    $md5 = array();
	    $md5[0] = md5($concatedPassphrase, true);
	    $result = $md5[0];
	    for ($i = 1; $i < 3; $i++) {
	        $md5[$i] = md5($md5[$i - 1].$concatedPassphrase, true);
	        $result .= $md5[$i];
	    }
	    $key = substr($result, 0, 32);
	    $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
	    return json_decode($data, true);
	}

	/**
	* Encrypt value to a cryptojs compatiable json encoding string
	*
	* @param mixed $passphrase
	* @param mixed $value
	* @return string
	*/
	function cryptoJsAesEncrypt($passphrase, $value){
	    $salt = openssl_random_pseudo_bytes(8);
	    $salted = '';
	    $dx = '';
	    while (strlen($salted) < 48) {
	        $dx = md5($dx.$passphrase.$salt, true);
	        $salted .= $dx;
	    }
	    $key = substr($salted, 0, 32);
	    $iv  = substr($salted, 32,16);
	    $encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iv);
	    $data = array("ct" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "s" => bin2hex($salt));
	    return json_encode($data);
	}

	function getDownload(){
		include __DIR__."/../Views/Utils/getDownload.php";		
	}

	function getVideoCaseStudy(){
		include __DIR__."/../Views/Utils/getVideos.php";		
	}

}