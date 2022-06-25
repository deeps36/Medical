<?php
namespace FES\GEET\ClassLib;
use FES\GEET\ClassLib\GeetMain;
use FES\GEET\ClassLib\ClassDBase\ToolsDBase;
use FES\GEET\ClassLib\ClassDBase\LabelDBase;

class Tools extends GeetMain{
	private $dbase;
	private $labelDbase;
	function __construct($parent = true){
		if($parent){
			parent::__construct();
			parent::__addAction(array("User.login","User.getLogout","User.register"));
		}
		$this->dbase = new ToolsDBase;
		$this->labelDbase = new LabelDBase;
	}
	
	protected $toolLabelId = array();

	public function getTools(){
		$tool = $this->dbase->displayTool();
		include __DIR__."/../Views/Tools/listTool.php";
	}

	public function getNewTool(){
		$label = $this->labelDbase->getAllLabels();
		include __DIR__."/../Views/Tools/addTool.php"; 
		
	}

    public function postNewTool(){
		
		if(isset($_POST['toolname']) && isset($_POST['tool_desc']) && isset($_POST['tool_url'])){
			
			$add = $this->dbase->addTool($_POST['toolname'],$_POST['tool_desc'],$_POST['tool_url']);
			
			if($add['responseType'] == '1'){
				$tool_id=$add['text'][0]['id'];
				$label_id = $_POST['labelname'];
				foreach($label_id as $label){
					$add_tool_label=$this->dbase->addToolLabel($tool_id,$label);
				}
				$_SESSION['message']['success'] = "Tool - <b>".$_POST['toolname']."</b> added successfully";
				$this->redirect("/Tools/getTools");
			} else{
				$_SESSION['message']['alert'] = $_POST['tool']." is Not Added.";
				$this->redirect($_SERVER['HTTP_REFERER']);
			}
		}else{
			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
		   $this->redirect($_SERVER['HTTP_REFERER']);   		
	   }
       
    }

	public function getUpdateTool(){
		if(isset($_GET['id']) && $_GET['id'] != ""){
    		$tool = $this->dbase->displayTool($_GET['id']);
			$toolLabel = $this->dbase->getLabelByTool($_GET['id']);
			if($toolLabel['responseType'] === '1') {
				foreach($toolLabel['text'] as $label_id) {
					$this->toolLabelId[] = $label_id['label_id'];
				}
				$toolLabelId = $this->toolLabelId;
			}
    		if($tool['responseType'] === '1'){
				$label = $this->labelDbase->getAllLabels();
    			include __DIR__."/../Views/Tools/updateTool.php";
    		} else{
    			$_SESSION['message']['alert'] = "Error: Page doesn't exist.";
				$this->redirect($_SERVER['HTTP_REFERER']);   			
    		}
    	} else{
 			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);   		
    	}
	}

	public function postUpdateTool(){
		if(isset($_POST['id']) && $_POST['id'] != "" && isset($_POST['toolname']) && $_POST['toolname'] != "" && isset($_POST['tool_desc']) && $_POST['tool_desc'] != "" && isset($_POST['tool_url']) && $_POST['tool_url'] != ""){		
			
			$response = $this->dbase->postUpdateTool($_POST['id'], $_POST['toolname'], $_POST['tool_desc'], $_POST['tool_url']);
			if($response['responseType'] === '1'){
				if(!empty($_POST['id'])) {
					$label_id = $_POST['labelname'];
					foreach($label_id as $label){
						$result = $this->dbase->updateToolLabel($_POST['id'],$label);
					}
					$this->dbase->deleteToolLabel($_POST['id'],$label_id);	
				}
    			$_SESSION['message']['success'] = "Tool - <b>".$_POST['toolname']."</b> updated successfully";
				$this->redirect("/Tools/getTools");
			} else{
				$_SESSION['message']['alert'] = "Coudn't update Tool.";
				$this->redirect($_SERVER['HTTP_REFERER']);
			}

		}else{
			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);   		
		}
	}

	public function deleteTool(){
		if(isset($_GET['id']) && $_GET['id'] != ""){
    		$response = $this->dbase->deleteTool($_GET['id']);
    		if($response['responseType'] === '1'){
    			$_SESSION['message']['success'] = "Tool - <b>".$tool['text'][0]["toolname"]."</b> deleted successfully";
				$this->redirect("/Tools/getTools");
    		} else{
    			$_SESSION['message']['alert'] = "Coudn't delete Tool.";
				$this->redirect($_SERVER['HTTP_REFERER']);
    		}
			
    	} else{
 			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);   		
    	}		
	}

}

?>