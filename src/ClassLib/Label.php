<?php
namespace FES\GEET\ClassLib;
use FES\GEET\ClassLib\GeetMain;
use FES\GEET\ClassLib\ClassDBase\LabelDBase;

class Label extends GeetMain{
	private $dbase;
	function __construct($parent = true){
		if($parent){
			parent::__construct();
			parent::__addAction(array("User.login","User.getLogout","User.register"));
		}
		$this->dbase = new LabelDBase;
	}

	protected $labelLangId = array();

    public function getLabels(){
		
		$label = $this->dbase->getAllLabels();
		include __DIR__."/../Views/Label/getAllLabel.php";
	}

    public function getNewLabel(){
		$language = $this->dbase->getLanguages();
        include __DIR__."/../Views/Label/addLabel.php";
    }

    public function postNewLabel(){

        if(isset($_POST['labelname']) && isset($_POST['label_desc'])){
			
			$add = $this->dbase->addLabel($_POST['labelname'],$_POST['label_desc']);
			
			if($add['responseType'] == '1'){
				$label_id=$add['text'][0]['id'];
				$lang_id = $_POST['langname'];
				foreach($lang_id as $key => $value){
					if(!empty($value)){
						$add_label_language=$this->dbase->addLabelLanguage($label_id,$key,$value);
					}
				}
				$_SESSION['message']['success'] = "Label - <b>".$_POST['labelname']."</b> added successfully";
				$this->redirect("/Label/getLabels");
			}else{
				$_SESSION['message']['alert'] = $_POST['labelname']." is Not Added.";
				$this->redirect($_SERVER['HTTP_REFERER']);
			}

		}else{
			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
		   $this->redirect($_SERVER['HTTP_REFERER']);   		
	   }
    }

	function getUpdateLabel(){
		if(isset($_GET['id']) && $_GET['id'] != ""){
    		$label = $this->dbase->getAllLabels($_GET['id']);
			$labelLang = $this->dbase->getLangByLabel($_GET['id']);
			if($labelLang['responseType'] === '1') {
				foreach($labelLang['text'] as $lang_id) {
					$this->labelLangId[] = $lang_id['lang_id'];
				}
				$labelLangId = $this->labelLangId;
			}
    		if($label['responseType'] === '1'){
				$language = $this->dbase->getLanguages();
    			include __DIR__."/../Views/Label/updateLabel.php";
    		} else{
    			$_SESSION['message']['alert'] = "Error: Page doesn't exist.";
				$this->redirect($_SERVER['HTTP_REFERER']);   			
    		}
			
    	} else{
 			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);   		
    	}
	}

	public function postUpdateLabel(){
		if(isset($_POST['id']) && $_POST['id'] != "" && isset($_POST['labelname']) && $_POST['labelname'] != "" && isset($_POST['label_desc']) && $_POST['label_desc'] != ""){
			$response = $this->dbase->postUpdateLabel($_POST['id'], $_POST['labelname'],$_POST['label_desc']);
			if($response['responseType'] === '1'){
				if(!empty($_POST['id'])) {
					$label_id=$_POST['id'];
					$lang_id = $_POST['langname'];
					foreach($lang_id as $key => $value){
						if(!empty($value)){
							$result=$this->dbase->updateLabelLanguage($label_id,$key,$value);
						}
					}
					foreach($lang_id as $key => $value){
						if(!empty($key)){
							$this->dbase->deleteLabelLanguage($label_id,$lang_id);
						}else{
							$_SESSION['message']['alert'] = "Coudn't update Label.";
							$this->redirect($_SERVER['HTTP_REFERER']);
						}
					} 
				}
    			$_SESSION['message']['success'] = "Label - <b>".$_POST['labelname']."</b> updated successfully";
				$this->redirect("/Label/getLabels");
    		} else{
    			$_SESSION['message']['alert'] = "Coudn't update Label.";
				$this->redirect($_SERVER['HTTP_REFERER']);
    		}

			}else{
				$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);   		
		}
	}

	function deleteLabel(){
		if(isset($_GET['id']) && $_GET['id'] != ""){
    		$response = $this->dbase->deleteLabel($_GET['id']);
    		if($response['responseType'] === '1'){
    			$_SESSION['message']['success'] = "Label - <b>".$_POST['labelname']."</b> deleted successfully";
				$this->redirect("/Label/getLabels");
    		} else{
    			$_SESSION['message']['alert'] = "Coudn't delete Label.";
				$this->redirect($_SERVER['HTTP_REFERER']);
    		}
			
    	} else{
 			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);   		
    	}		
	}

}

?>