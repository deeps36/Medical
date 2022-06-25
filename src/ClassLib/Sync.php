<?php
namespace FES\GEET\ClassLib;
use FES\GEET\ClassLib\GeetMain;
use FES\GEET\ClassLib\ClassDBase\SyncDBase;
use FES\GEET\ClassLib\ClassDBase\ToolsDBase;

class Sync extends GeetMain{
    private $dbase;
	private $toolsDBase;
    function __construct($parent = true){
        if($parent){
            parent::__construct();
            parent::__addAction(array("User.login","User.getLogout","User.register"));
        }
        $this->dbase = new SyncDBase;
        $this->toolsDBase = new ToolsDBase;
    }

    function getSync(){
        $tool=$this->toolsDBase->displayTool();
        include __DIR__."/../Views/Sync/syncToolQuestion.php";
    }

    function postSync(){
        if(isset($_POST['toolname'])){
            $service_url = "https://forms.indiaobservatory.org.in/".$_POST['toolname']."/1.0/form/get-forms.json";
            $curl = curl_init($service_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            //execute the session
            $curl_response = curl_exec($curl);
            //finish off the session
            curl_close($curl);
            $data = json_decode($curl_response, true);
            
            if($data['status'] == 'true'){
                foreach($data['data']['forms'] as $form){
                    $id=$form['id'];
                    $uid=$form['uid'];
                    $name=$form['name'];
                    $position=$form['position'];
                    $sync_api=$form['sync_api'];
                    $survey_name=$form['survey_name'];
                    $formResponse = $this->dbase->postSyncForm($id,$uid,$name,$position,$_POST['toolname'],$sync_api,$survey_name);
                    // for questions
                    foreach($data['data']['questions'] as $record){                
                        $que_id=$record['que_id'];
                        $que_uid=$record['que_uid'];
                        $form_id=$record['form_id'];
                        $position=$record['position'];
                        $question=$record['question'];
                        $type=$record['type'];
                        $value=$record['value'];
                        $hint=$record['hint'];
                        $value_reuired=$record['value_required'];
                        $dependency_flag=$record['dependency_flag'];
                        $condition=$record['condition'];
                        $mandatory=$record['mandatory'];
                        $window_type=$record['window_type'];
                        $form_uid=$form['uid'];
                        $queResponse = $this->dbase->postSyncQuestion($que_id,$que_uid,$form_id,$position,$question,$type,$value,$hint,$value_reuired,$dependency_flag,$condition,$mandatory,$window_type,$form_uid);

                        // foreach($data['data']['questions']['options'] as $record){
                        //     $option_id=$record['option_id'];
                        //     $option_uid=$record['option_uid'];
                        //     $que_uid=$record['que_uid'];
                        //     $option_name=$record['option_name'];
                        //     $optionResponse = $this->dbase->postSyncOption($option_id,$option_uid,$que_uid,$option_name);
                        // }
                    }
                }
            }else{
                $_SESSION['message']['alert'] = "ERROR! Requested tool is not configured";
                $this->redirect($_SERVER['HTTP_REFERER']);
            }
            if($formResponse['responseType'] == '1' || $queResponse['responseType'] == '1'){
                $response = $this->dbase->getSyncTime();
                $_SESSION['message']['success'] = "Data Sync Successfully";
                $this->redirect($_SERVER['HTTP_REFERER']);
            }else{
                $_SESSION['message']['alert'] = "Error: Sync Failed";
                $this->redirect($_SERVER['HTTP_REFERER']);
            }           
        }else{
            $_SESSION['message']['alert'] = "Error: Select Any Tool(s)";
            $this->redirect($_SERVER['HTTP_REFERER']);
        } 
    }
}
?>