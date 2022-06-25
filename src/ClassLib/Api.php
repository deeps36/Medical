<?php
namespace FES\GEET\ClassLib;
use FES\GEET\ClassLib\GeetMain;
use FES\GEET\ClassLib\ClassDBase\ApiDBase;
use FES\GEET\ClassLib\ClassDBase\ToolsDBase;
use FES\GEET\ClassLib\ClassDBase\LanguageDBase;
use FES\GEET\ClassLib\ClassDBase\UserDBase;
use FES\GEET\ClassLib\ClassDBase\QuestionDBase;
use FES\GEET\ClassLib\Email;
use FES\GEET\ClassLib\Utils;
use IcyApril\CryptoLib;
use FES\GEET\ClassLib\ClassDBase\UtilsDBase;
use Gregwar\Captcha\CaptchaBuilder;


class Api extends GeetMain{
	private $credentials = array();
	private $dbase;
	private $toolsDBase;
	private $languageDBase;
	private $UserDBase;
	private $questionDBase;
	private $mail;
	private $salt = '0kt5ku8J9tkl025A7dMAIT4IvYi81zq9';
	private $utilsDbase;

    function __construct($parent = true){
		if($parent){
			parent::__construct();
			parent::__addAction(array("User.login","User.getLogout","User.register"));
		}
		$this->dbase = new ApiDBase;
		$this->toolsDBase = new ToolsDBase;
		$this->languageDBase = new LanguageDBase;
		$this->UserDBase = new UserDBase;
		$this->mail = new Email;
		$this->utilsDbase = new UtilsDBase;
		$this->utils = new Utils;
		$this->questionDBase = new QuestionDBase;

	}
	
	protected $ApiAccess = array();

    function getApi(){

        $api = $this->dbase->getApis();
		include __DIR__."/../Views/Api/getAllApi.php";

    }

    public function getNewApi(){
		
		include __DIR__."/../Views/Api/addApi.php"; 

	}

    public function postNewApi(){
		
		if(isset($_POST['apiname'])&& isset($_POST['apiurl'])){
			$add = $this->dbase->addApi($_POST['apiname'],$_POST['apiurl']);
            
			if($add['responseType'] == '1'){
				$_SESSION['message']['success'] = "Api - <b>".$_POST['apiname']."</b> added successfully";
				$this->redirect("/Api/getApi");
			} else{
				$_SESSION['message']['alert'] = $_POST['apiname']." is Not Added.";
				$this->redirect($_SERVER['HTTP_REFERER']);
			}
		}else{
			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
		   $this->redirect($_SERVER['HTTP_REFERER']);   		
	   }
       	
    }

    function getUpdateApi(){
		if(isset($_GET['id']) && $_GET['id'] != ""){
    		$api = $this->dbase->getApis($_GET['id']);
    		if($api['responseType'] === '1'){
    			include __DIR__."/../Views/Api/updateApi.php";
    		} else{
    			$_SESSION['message']['alert'] = "Error: Page doesn't exist.";
				$this->redirect($_SERVER['HTTP_REFERER']);   			
    		}
			
    	} else{
 			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);   		
    	}
	}

    public function postUpdateApi(){
		
		if(isset($_POST['id']) && $_POST['id'] != "" && isset($_POST['apiname']) && $_POST['apiname'] != "" && isset($_POST['apiurl']) && $_POST['apiurl'] != ""){		
			$response = $this->dbase->postUpdateApi($_POST['id'], $_POST['apiname'], $_POST['apiurl']);
			if($response['responseType'] === '1'){
    			$_SESSION['message']['success'] = "Api - <b>".$_POST['apiname']."</b> updated successfully";
				$this->redirect("/Api/getApi");
    		} else{
    			$_SESSION['message']['alert'] = "Coudn't update Api.";
				$this->redirect($_SERVER['HTTP_REFERER']);
    		}

			}else{
				$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);   		
		}
	}

    function deleteApi(){
		
		if(isset($_GET['id']) && $_GET['id'] != ""){
    		$response = $this->dbase->deleteApi($_GET['id']);
    		if($response['responseType'] === '1'){
    			$_SESSION['message']['success'] = "Api - <b>".$_POST['apiname']."</b> deleted successfully";
				$this->redirect("/Api/getApi");
    		} else{
    			$_SESSION['message']['alert'] = "Coudn't delete Api.";
				$this->redirect($_SERVER['HTTP_REFERER']);
    		}
			
    	} else{
 			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);   		
    	}		
	}

	function getApiAccess(){
		
		$user_id=$_GET['user_id'];
		$user=$this->dbase->getUser();	
		if($user['responseType'] === false)
		{
			$_SESSION['message']['alert'] = "Error: Failed to fetch users' list. Please contact site administrator.";
			$this->redirect($_SERVER['HTTP_REFERER']); exit;
			// show error page
		}
		if(isset($_POST['apiname'])){
			$api_id = $_POST['apiname'];
			foreach($api_id as $id){
				if(!empty($id)){
					$add=$this->dbase->updateApiAccess($user_id,$id);	
				}else{
					$_SESSION['message']['alert'] = "Coudn't update API.";
					$this->redirect($_SERVER['HTTP_REFERER']);
				}
			}
			foreach($api_id as $id){
				if(!empty($id)){
					$this->dbase->deleteApiAccess($user_id,$api_id);	
				}else{
					$_SESSION['message']['alert'] = "Coudn't delete API.";
					$this->redirect($_SERVER['HTTP_REFERER']);
				}
			}
			$_SESSION['message']['success'] = "API Access updated successfully";
		}
		
		$api = $this->dbase->getApis();		
		if($user['responseType'] === '1'){
			$userApi = $this->dbase->getApiByUser($user_id);
			foreach($userApi['text'] as $api_id) {
				$this->ApiAccess[] = $api_id['api_id'];
			}
			$ApiAccess = $this->ApiAccess;
			//var_dump($ApiAccess);exit;
		}				
		include __DIR__."/../Views/Api/api_access.php";
		
	}

	function getToolLabels() {
		if(isset($_GET['tool_id']) && $_GET['tool_id'] != ""){
			echo json_encode($this->dbase->getToolLabels($_GET['tool_id']));
		}
	}

	function getlabelsTranslation() {
		if(isset($_GET['tool_id']) && $_GET['tool_id'] != ""){
			$toolLabel = $this->dbase->getlabelTranslation($_GET['tool_id']);
			$arr=array();
			$arr['responseType'] = "1";
			if($toolLabel['responseType'] == "1"){
				$i=0;
				foreach($toolLabel['text'] as $key => $value){
					
					$arr['text'][$i]['label_uid']=$value['uid'];
					$arr['text'][$i]['labelname']=$value['labelname'];
					$lang_id=explode('_delimit_',$value['lang_id']);
					$lang_name=explode('_delimit_',$value['languagename']);
					$translation=explode('_delimit_',$value['translation']);
					$j=0;
					$test=array();
					foreach($lang_id as $key => $value){
						$test[$j]['lang_id']=$value;					
						$test[$j]['lang_name']=$lang_name[$j];
						$test[$j]['translation']=$translation[$j];
						$j++;					
					}
					$arr['text'][$i]['translation']=$test;
					$i++;	
				}
			} else{
				$arr = $toolLabel;
			}
			echo json_encode($arr);
		}
	}  

	function getTools() {
		echo json_encode($this->toolsDBase->displayTool());
	}

	function getLanguages() {
		echo json_encode($this->dbase->getLanguages());
	}
	
	function getQuestionsTranslation(){
		if (isset($_GET['tool_id']) && $_GET['tool_id'] != "") {
			$question_form = $this->dbase->getQuestionsTranslation($_GET['tool_id']);
			//var_dump($question_form);exit; 
			$i=0;
			if ($question_form['responseType'] === '1') {
				echo json_encode($question_form);           
			}else{
				echo json_encode($question_form);
			}         
		} else{
			$response = array();
			$response['responseType'] = "-1";
			$response['text'] = "Error: Missing tool id.";
			echo json_encode($response);
		}
	}
    
	function getApiUser(){
		$apiuser = $this->dbase->getApiUser();
		include __DIR__. "/../Views/Api/getAllApiUser.php";

	}

	
	function getNewApiUser(){
        $action = "/Api/postNewApiUser";
        $userapi = $this->dbase->getApis();
        $organizations = $this->dbase->getOrganizations($_SESSION['userid'],$_SESSION['super_admin']);

		include __DIR__."/../Views/Api/addApiUser.php";
	}
	function postNewApiUser(){
		$email = $_POST['email'];
    	$user_id = $_POST['user_id'];
    	//$password = $_POST['password'];
    	$organizations = $this->dbase->getOrganizations($_SESSION['userid'],$_SESSION['super_admin']);
		$userOrganizations = $this->dbase->getOrganizations($user_id);
		
    	$result	= $this->dbase->checkEmail($email);
    	if ($result['text'][0]['total_rows'] != 0) {
    		$_SESSION['message']['alert'] = "Error: Email id already exists.-"  .$email;
    		$this->redirect($_SERVER['HTTP_REFERER']);
    		# code...
    	}


    	$result =  $this->dbase->checkUserId($user_id);
    	if ($result['text'][0]['total_rows'] != 0){
    		$_SESSION['message']['alert'] = "<b>".$user_id."</strong> is already registered. Please provide different user id";
    		$this->redirect($_SERVER['HTTP_REFERER']);
    		# code...
    	}
    	# code...
        $password = $this->utils->cryptoJsAesDecrypt($_SESSION["salt_register"], $_POST['password']);
			$confirm_password = $this->utils->cryptoJsAesDecrypt($_SESSION["salt_register"], $_POST['confirm_password']);
			unset($_SESSION["salt_register"]);

			if($password !== $confirm_password){
				$_SESSION['message']['alert'] = "Password and Confirm password fields are not matching.";
				$this->redirect($_SERVER['HTTP_REFERER']);	
				exit;
	    	}
			
			$response = $this->utils->checkPasswordStrength($password);
			if($response['type'] !== "1"){
				$_SESSION['message']['alert'] = "Error: ".$response['text'];
				$this->redirect($_SERVER['HTTP_REFERER']);
			}
           
		$name = $_POST['name'];
		$password = CryptoLib::hash($password, $this->salt);
		$mob_number = $_POST['mobile_number'];
		//$apiname = $_POST['apiname'];

		$result = $this->dbase->postNewApiUser($user_id,$name,$mob_number,$email,$password);
		if ($result['responseType'] === '1') {
			
			if(isset($_POST['apiname'])){
			$api_id = $_POST['apiname'];
			foreach($api_id as $id){
				if(!empty($id)){
					$add=$this->dbase->updateApiAccess($user_id,$id);
					//var_dump($add);exit;	
				}else{
					$_SESSION['message']['alert'] = "Failed creating New API User.";
					$this->redirect($_SERVER['HTTP_REFERER']);
				}
			}
		}
		   $user_id = $result['text'][0]['user_id'];
		   $organizations=$_POST['organizations'];
					
			$result_user_organizations = $this->dbase->postNewUserOrganization($user_id,$organizations);
				     
			$_SESSION['message']['success'] = "<b>" .$user_id. "</b> has been successfully created";
			$this->redirect('/Api/getApiUser');
		
			# code...
		}else{
			$_SESSION['message']['alert'] = 'Error:Failed Creating new Api User';
			$this->redirect($_SERVER['HTTP_REFERER']);
		}
  }

	function getEmail(){
		$email = $_GET['email'];
		if(!empty($email)) {
			$result = $this->dbase->checkEmail($email);
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
	function getUserid() {
		$user_id = $_GET['id'];
		if(!empty($user_id)) {
			$result = $this->dbase->checkUserId($user_id);
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
	
	
	function getUpdateApiUser(){
		if(isset($_GET['user_id']) && $_GET['user_id'] != "") {
		 $update = $this->dbase->getUpdateApiUser($_GET['user_id']);
		 $user_id = $_GET['user_id'];
         $organizations = $this->dbase->getOrganizations($_SESSION['userid'],$_SESSION['super_admin']);
		 $userOrganizations = $this->dbase->getOrganizations($user_id);

		if($update['responseType'] === '1'){
            $api = $this->dbase->getApis();	
         	$userApi = $this->dbase->getApiByUser($user_id);
			foreach($userApi['text'] as $api_id) {
				$this->ApiAccess[] = $api_id['api_id'];
			}
			$ApiAccess = $this->ApiAccess;
			//var_dump($ApiAccess);exit;
							
           	include __DIR__."/../Views/Api/updateApiUser.php";
		  }else{
		$_SESSION['message']['alert'] = "Error - couldn't update api user.";
		$this->redirect($_SERVER['HTTP_REFERER']);
				
	}
		# code...
	}else{
		$_SESSION['message']['alert'] = 'Error - missing paramaters';
		$this->redirect($_SERVER['HTTP_REFERER']);

	  }

	}
	function postUpdateApiUser(){
  	
  	if (isset($_POST['user_id']) && $_POST['user_id'] != "" && isset($_POST['name']) && $_POST['name'] != "" && isset($_POST['mob_number']) && $_POST['mob_number'] != "") {	
  	
  	$response = $this->dbase->updateApiUser($_POST['user_id'],$_POST['name'],$_POST['mob_number']);

  if ($response['responseType'] === '1') {
    	if(isset($_POST['apiname'])){
			$api_id = $_POST['apiname'];
			$user_id = $_POST['user_id'];
			foreach($api_id as $id){
				if(!empty($id)){
					$add=$this->dbase->updateApiAccess($user_id,$id);	
				}else{
					$_SESSION['message']['alert'] = "Coudn't update API.";
					$this->redirect($_SERVER['HTTP_REFERER']);
				}
			}
			foreach($api_id as $id){
				if(!empty($id)){
					$this->dbase->deleteApiAccess($user_id,$api_id);	
				}else{
					$_SESSION['message']['alert'] = "Coudn't delete API.";
					$this->redirect($_SERVER['HTTP_REFERER']);
				}
			}
		}
		if(!empty($_POST['organizations'])) {
		 $this->dbase->updateUserOrganization($user_id, $_POST['organizations']);
		}
		
  	 $_SESSION['message']['success'] = "Api user - <b>" .$_POST['user_id']."</b> has been updated successfully";
  	 $this->redirect('/Api/getApiUser');
  	 
	}else{
		$_SESSION['message']['alert']="couldn't update api User";
		$this->redirect($_SERVER['HTTP_REFERER']);
	}

  } else{
  	$_SESSION['message']['alert'] = 'Error - missing parameter';
  	$this->redirect($_SERVER['HTTP_REFERER']);
  }
 }

  function getUnblockApiUser(){  	
	  	if(isset($_GET['user_id']) && $_GET['user_id'] != ""){
	    		$response = $this->dbase->getUnblockApiUser($_GET['user_id']);
	    		if($response['responseType'] === '1'){	    			
	    				    			   			
	    			$_SESSION['message']['success'] = "Api user - <b>" .$_GET['user_id']."</b> has been Unlocked successfully";
				 	$this->redirect("/Api/getApiUser");
				  
	    		} else{
	    			$_SESSION['message']['alert'] = "Coudn't block Api User.";
					$this->redirect($_SERVER['HTTP_REFERER']);
	    		}
				
	    	} else{
	 			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
				$this->redirect($_SERVER['HTTP_REFERER']);   		
	    	}	

	  }
	function getBlockApiUser(){
		if (isset($_GET['user_id']) && $_GET['user_id']) {
			$response = $this->dbase->getBlockApiUser($_GET['user_id'],time());
			if ($response['responseType'] === '1') {
				$_SESSION['message']['success'] = "Api user - <b>" .$_GET['user_id']."</b> has been Block successfully";
				$this->redirect("/Api/getApiUser");
				// code...
			}else{
				$_SESSION['message']['alert'] = "Coudn't blocked Api User";
				$this->redirect($_SERVER['HTTP_REFERER']);
			}
			// code...
		}else{
			$_SESSION['message']['alert'] = "Error: missing parameter";
			$this->redirect($_SERVER['HTTP_REFERER']);
		}
		
	}
	
		 
 }


?>