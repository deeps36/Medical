<?php
namespace FES\GEET\ClassLib;

use FES\GEET\ClassLib\ClassDBase\FaqDBase;
use FES\GEET\ClassLib\ClassDBase\UtilsDBase;
use IcyApril\CryptoLib;
use FES\GEET\ClassLib\Utils;

/**
* Scheme class
*/
class Faq extends GeetMain{	
	function __construct($parent = true){
		if($parent){
			parent::__construct();
		}
		$this->dbase = new FaqDBase;
		$this->utils_dbase = new UtilsDBase;
		$this->utils = new Utils;
	}

	function getFaqs(){
		$faqs = $this->dbase->getFaqs();
		include __DIR__."/../Views/Faq/getFaqs.php";
	}

	function getAllFaqs(){
		$faqs = $this->dbase->getFaqs();
		include __DIR__."/../Views/Faq/getAllFaqs.php";
	}

	function getFaq(){
		if(isset($_GET['id']) && $_GET['id'] != ""){
    		$faq = $this->dbase->getFaqUpdate($_GET['id']);
    		if($faq['responseType'] === '1'){
    			$_SESSION['faqusalt'] = CryptoLib::generateSalt();
    			include __DIR__."/../Views/Faq/getFaq.php";
    		} else{
    			$_SESSION['message']['alert'] = "Error: Page doesn't exist.";
				$this->redirect($_SERVER['HTTP_REFERER']);   			
    		}
			
    	} else{
 			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);   		
    	}
	}

	function postUpdateFaq(){
		if(isset($_POST['id']) && $_POST['id'] != "" && isset($_POST['question']) && $_POST['question'] != ""){
			$answer = $this->utils->cryptoJsAesDecrypt($_SESSION["faqusalt"], $_POST["answer"]);
			unset($_SESSION['faqusalt']);
			if($answer === null){
				if(isset($_SESSION['maliciousAttempt'])){
				 	$_SESSION['maliciousAttempt']++;
					if ($_SESSION['maliciousAttempt'] >= 5){
						$this->dbase->blockUser($_SESSION['userid'], time());
						$_SESSION['message']['warning'] = "Your account is temporarily blocked due to multiple malicious login attempts";
						$this->redirect("/");
					}
				} else {
					$_SESSION['maliciousAttempt'] = 1;
				}
				$_SESSION['message']['warning'] = "Access Denied: Malicious attempt found.";
				$this->redirect("/#login");
			}
    		$response = $this->dbase->postUpdateFaq($_POST['id'], $_POST['question'], $answer, $_POST['weight']);
    		if($response['responseType'] === '1'){
    			$_SESSION['message']['success'] = "FAQ - <b>".$_POST['question']."</b> updated successfully";
				$this->redirect("/Faq/getAllFaqs");
    		} else{
    			$_SESSION['message']['alert'] = "Coudn't update FAQ.";
				$this->redirect($_SERVER['HTTP_REFERER']);
    		}
			
    	} else{
 			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);   		
    	}	
	}

	function deleteFaq(){
		if(isset($_GET['id']) && $_GET['id'] != ""){
    		$response = $this->dbase->deleteFaq($_GET['id']);
    		if($response['responseType'] === '1'){
    			$_SESSION['message']['success'] = "FAQ - <b>".$_GET['id']."</b> deleted successfully";
				$this->redirect("/Faq/getAllFaqs");
    		} else{
    			$_SESSION['message']['alert'] = "Coudn't delete FAQ.";
				$this->redirect($_SERVER['HTTP_REFERER']);
    		}
			
    	} else{
 			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);   		
    	}		
	}

	function getNewFaq(){
		$_SESSION['faqsalt'] = CryptoLib::generateSalt();
		include __DIR__."/../Views/Faq/getNewFaq.php";
	}

	function postNewFaq(){
//		var_dump($_POST); exit;
		if(isset($_POST['question']) && $_POST['question'] != "" && isset($_POST['answer']) && $_POST['answer'] != "" && isset($_POST['weight']) && $_POST['weight'] != ""){
			$answer = $this->utils->cryptoJsAesDecrypt($_SESSION["faqsalt"], $_POST["answer"]);
			unset($_SESSION['faqsalt']);
			if($answer === null){
				if(isset($_SESSION['maliciousAttempt'])){
				 	$_SESSION['maliciousAttempt']++;
					if ($_SESSION['maliciousAttempt'] >= 5){
						$this->dbase->blockUser($_SESSION['userid'], time());
						$_SESSION['message']['warning'] = "Your account is temporarily blocked due to multiple malicious login attempts";
						$this->redirect("/");
					}
				} else {
					$_SESSION['maliciousAttempt'] = 1;
				}
				$_SESSION['message']['warning'] = "Access Denied: Malicious attempt found.";
				$this->redirect("/#login");
			}
    		$response = $this->dbase->postNewFaq($_POST['question'],$answer,$_POST['weight']);
    		if($response['responseType'] == '1'){
    			$_SESSION['message']['success'] = "FAQ - <b>".$_POST['question']."</b> added successfully";
				$this->redirect("/Faq/getAllFaqs");
    		} else{
    			$_SESSION['message']['alert'] = "Coudn't add FAQ.";
				$this->redirect($_SERVER['HTTP_REFERER']);
    		}
			
    	} else{
 			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);   		
    	}	
	}

}