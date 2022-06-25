<?php
namespace FES\GEET\ClassLib;
use FES\GEET\ClassLib\GeetMain;
use FES\GEET\ClassLib\ClassDBase\LanguageDBase;

class Language extends GeetMain{
	private $dbase;
	function __construct($parent = true){
		if($parent){
			parent::__construct();
			parent::__addAction(array("User.login","User.getLogout","User.register"));
		}
		$this->dbase = new LanguageDBase; 
	}

	public function getLanguages(){
		
		$language = $this->dbase->getAllLanguages();
		include __DIR__."/../Views/Languages/getAllLanguages.php";
	}

	public function getNewLanguage(){
		include __DIR__."/../Views/Languages/addLanguage.php"; 

	}

    public function postNewLanguage(){
		
		if(isset($_POST['name']) && isset($_POST['lang_desc']) && isset($_POST['language_code'])){
			$add = $this->dbase->addLanguage($_POST['name'],$_POST['lang_desc'],$_POST['language_code']);
			if($add['responseType'] == '1'){
				$_SESSION['message']['success'] = "Language - <b>".$_POST['name']."</b> added successfully";
				$this->redirect("/Language/getLanguages");
			} else{
				$_SESSION['message']['alert'] = $_POST['Language']." is Not Added.";
				$this->redirect($_SERVER['HTTP_REFERER']);
			}
		}else{
			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
		   $this->redirect($_SERVER['HTTP_REFERER']);   		
	   }    	
    }


	public function getUpdateLanguage(){
		
		if(isset($_GET['id']) && $_GET['id'] != ""){
    		$language = $this->dbase->getAllLanguages($_GET['id']);
    		if($language['responseType'] === '1'){
    			include __DIR__."/../Views/Languages/updateLanguage.php";
    		} else{
    			$_SESSION['message']['alert'] = "Error: Page doesn't exist.";
				$this->redirect($_SERVER['HTTP_REFERER']);   			
    		}
			
    	} else{
 			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);   		
    	}
	}

	public function postUpdateLanguage(){

		if(isset($_POST['id']) && $_POST['id'] != "" && isset($_POST['name']) && $_POST['name'] != "" && isset($_POST['lang_desc']) && $_POST['lang_desc'] != "" && isset($_POST['language_code']) && $_POST['language_code'] != ""){		
			$response = $this->dbase->postUpdateLanguage($_POST['name'], $_POST['lang_desc'], $_POST['language_code'],$_POST['id']);
			
			if($response['responseType'] === '1'){
    			$_SESSION['message']['success'] = "Language - <b>".$_POST['name']."</b> updated successfully";
				$this->redirect("/Language/getLanguages");
    		} else{
    			$_SESSION['message']['alert'] = "Coudn't update Language.";
				$this->redirect($_SERVER['HTTP_REFERER']);
    		}

			}else{
				$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);   		
		}

	}

	function deleteLanguage(){
		if(isset($_GET['id']) && $_GET['id'] != ""){
    		$response = $this->dbase->deleteLanguage($_GET['id']);
    		if($response['responseType'] === '1'){
    			$_SESSION['message']['success'] = "Language - <b>".$_POST['name']."</b> deleted successfully";
				$this->redirect("/Language/getLanguages");
    		} else{
    			$_SESSION['message']['alert'] = "Coudn't delete Language.";
				$this->redirect($_SERVER['HTTP_REFERER']);
    		}
			
    	} else{
 			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);   		
    	}		
	}
	
}
?>