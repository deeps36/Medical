<?php
namespace FES\GEET\ClassLib;

use FES\GEET\ClassLib\ClassDBase\ContactDBase;
use Gregwar\Captcha\CaptchaBuilder;
use IcyApril\CryptoLib;
/**
* Scheme class
*/
class Contact extends GeetMain{	
	
	function __construct($parent = true){
		if($parent){
			parent::__construct();
		}
		$this->dbase = new ContactDBase;
	}

	function getJSLPS(){
		include __DIR__."/../Views/Contact/jslps.php";
	}

	function getOLM(){
		include __DIR__."/../Views/Contact/olm.php";
	}

	function getContact(){
		$builder = new CaptchaBuilder(5);
		$builder->build();
		$_SESSION['captchaValue'] = $builder->getPhrase();
		include __DIR__."/../Views/Contact/getContact.php";
	}

	function postContact(){
		if(!isset($_POST['captcha'])){
			$_SESSION['message']['warning'] = "Error: Missing captcha value.";
			$this->redirect($_SERVER['HTTP_REFERER']);
			exit;
		}
		if(strtolower($_POST['captcha']) !== $_SESSION['captchaValue']){
			$_SESSION['message']['warning'] = "Error: Incorrect captcha value";
			$this->redirect($_SERVER['HTTP_REFERER']);
		}
		if(isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['email']) && isset($_POST['subject']) && isset($_POST['message'])){
			try{
				$to = 'info@indiaobservatory.org.in, jay@fes.org.in, jay.bioinfo@gmail.com, ashok@fes.org.in';
				$subject = $_SERVER['HTTP_HOST'].' has received a new feedback';
				$body = "Sender Name: ".$_POST['fname']." ".$_POST['lname']."\r\nSender Email: ".$_POST['email']."\r\nSubject: ".$_POST['subject']."\r\nMessage: ".$_POST['message']."\r\n";
				mail($to, $subject, $body);
			} catch(Exception $e){
				error_log(time()."\t[Error]\tMail failure while sending feedback. Error message - ".$e->getMessage()."\n");
			}
			$data = $this->dbase->postFeedback($_POST['fname'], $_POST['lname'], $_POST['email'], $_POST['subject'], $_POST['message']);
			if($data['responseType'] == "1"){
				$_SESSION['message']['success'] = "Thank you. Your message has been sent. We will get back to you soon.";
				$this->redirect($_SERVER['HTTP_REFERER']);
			} else{
				$_SESSION['message']['warning'] = "Error: There was an error while processing your request, please try again later.";
				$this->redirect($_SERVER['HTTP_REFERER']);
			}
		} else{
			$_SESSION['message']['warning'] = "Error: Missing required information.";
			$this->redirect($_SERVER['HTTP_REFERER']);
			exit;
		}
	}
}