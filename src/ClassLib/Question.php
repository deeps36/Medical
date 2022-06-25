<?php
namespace FES\GEET\ClassLib;
use FES\GEET\ClassLib\GeetMain;
use FES\GEET\ClassLib\ClassDBase\QuestionDBase;
use FES\GEET\ClassLib\ClassDBase\ToolsDBase;
use FES\GEET\ClassLib\ClassDBase\LabelDBase;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Question extends GeetMain{
    private $dbase;
    private $labelDBase;
    private $toolsDBase;
    function __construct($parent = true){
		if($parent){
			parent::__construct();
			parent::__addAction(array("User.login","User.getLogout","User.register"));
		}
        $this->dbase = new QuestionDBase;
        $this->labelDBase = new LabelDBase;
		$this->toolsDBase = new ToolsDBase;
	}

    protected $QueLangId = array();
    protected $FormLangId = array();

    function getForm(){
        $form = $this->dbase->getForms();
        include __DIR__."/../Views/Question/getAllForm.php";
    }

    function getFormTranslation(){
        if(isset($_GET['id']) && $_GET['id'] != ""){
            $form = $this->dbase->getFormTranslation($_GET['id']);
            $language = $this->labelDBase->getLanguages();
            $FormLang = $this->dbase->getLangByForm($_GET['id']);
            // var_dump($FormLang);exit;
            if($FormLang['responseType'] === '1') {
				foreach($FormLang['text'] as $lang_id) {
					$this->FormLangId[] = $lang_id['lang_id'];
				}
				$FormLangId = $this->FormLangId;
			}
            include __DIR__."/../Views/Question/formTranslation.php";
        }else{
            $_SESSION['message']['alert'] = "Error: missing parameter(s)";
            $this->redirect($_SERVER['HTTP_REFERER']);   		
       }
    }

    function getQuestion(){ 
        $tool = $this->toolsDBase->displayTool();
        $question = $this->dbase->getQuestions();
        include __DIR__."/../Views/Question/getAllQuestion.php";
    }

    function getOptions(){
        if(isset($_GET['id']) && $_GET['id'] != ""){
            $question = $this->dbase->getQuestionTraslation($_GET['id']);
            $value = $question['text'][0]['value'];
            $option = explode("#",$value);
            include __DIR__."/../Views/Question/getAllOption.php";
        }else{
            $_SESSION['message']['alert'] = "Error: missing parameter(s)";
            $this->redirect($_SERVER['HTTP_REFERER']);   		
        }
    }

    function getQuestionTranslation(){
        if(isset($_GET['id']) && $_GET['id'] != ""){
            $question = $this->dbase->getQuestionTraslation($_GET['id']);
            $value = $question['text'][0]['value'];
            $options = explode("#",$value);
            $language = $this->labelDBase->getLanguages();
            $QueLang = $this->dbase->getLangByQue($_GET['id']);
            $FormLang = $this->dbase->getLangByForm($_GET['id']);

            if($QueLang['responseType'] === '1') {
				foreach($QueLang['text'] as $lang_id) {
					$this->QueLangId[] = $lang_id['lang_id'];
				}
				$QueLangId = $this->QueLangId;
			}
            if($FormLang['responseType'] === '1') {
				foreach($FormLang['text'] as $lang_id) {
					$this->FormLangId[] = $lang_id['lang_id'];
				}
				$FormLangId = $this->FormLangId;
			}
            include __DIR__."/../Views/Question/questionTranslation.php";
        }else{
            $_SESSION['message']['alert'] = "Error: missing parameter(s)";
            $this->redirect($_SERVER['HTTP_REFERER']);   		
       }
    }

    function postFormTranslation(){
        if(!empty($_POST['id'])) {         
            $form_uid=$_POST['form_id'];
            $form_lang_id = $_POST['formlangname'];
            $lang_id = $_POST['quelangname'];
            $form_lid = $_POST['formlangname'];
            foreach($form_lang_id as $key => $value){
                if(!empty($value)){
                    $result=$this->dbase->addFormLanguage($form_uid,$key,$value);
                }
            }
            foreach($form_lid as $key => $value){
                if(!empty($key)){
                    $result=$this->dbase->deleteFormLanguage($form_uid,$form_lid);
                }
            }
            $_SESSION['message']['success'] = "Translation - <b>".$_POST['question']."</b> updated successfully";
            $this->redirect("/Question/getForm");
        }else{
            $_SESSION['message']['alert'] = "Error: missing parameter(s)";
            $this->redirect($_SERVER['HTTP_REFERER']);
        }			
    }

    function postQuestionTranslation(){
        if(!empty($_POST['id'])) {
            $que_id=$_POST['que_id'];           
            $que_lang_id = $_POST['quelangname'];
            $lang_id = $_POST['quelangname'];
            foreach($que_lang_id as $key => $value){
                if(!empty($value)){
                    $result=$this->dbase->addQuestionLanguage($que_id,$key,$value);
                }
            }
            foreach($lang_id as $key => $value){
                if(!empty($key)){
                    $result=$this->dbase->deleteQuestionLanguage($que_id,$lang_id);
                }
            }
            $_SESSION['message']['success'] = "Translation - <b>".$_POST['question']."</b> updated successfully";
            $this->redirect("/Question/getQuestion");
        }else{
            $_SESSION['message']['alert'] = "Error: missing parameter(s)";
            $this->redirect($_SERVER['HTTP_REFERER']);
        }			
    }
    
    function postImportExport(){
		if(isset($_POST['opration']) && $_POST['opration'] == "export"){		
			switch($_POST['data']){
				case 'form':
					$this->getExportFormTranslation();
					break;
				case 'question':
					$this->getExportQuestionTranslation();
					break;
                case 'option':
                    $this->getExportOptionTranslation();
                    break;
			}
		}else if(isset($_POST['opration']) && $_POST['opration'] == "import"){			
			switch($_POST['data']){
				case 'form':
					$this->postImportFormTranslation();
					break;
				case 'question':
					$this->postImportQuestionTranslation();
					break;
                case 'option':
                    $this->postImportOptionTranslation();
                    break;
			}
		}else{
            $_SESSION['message']['alert'] = "Error! Something went worng.";
            $this->redirect($_SERVER['HTTP_REFERER']);
        }
	}

    function getExportFormTranslation(){
        if(isset($_POST['exportBtn']) && isset($_POST['toolname'])){
			$tool=$this->toolsDBase->displayTool();
			$tool_id=$_POST['toolname'];
			$response = $this->dbase->exportFormTranslation($tool_id);
			$header = array_keys($response['text'][0]);
			$fileName = str_replace(' ','_',$_POST['toolName'])."_Form_".date("d/m/Y").".xlsx";
			if($response['responseType'] === '1'){
				$excel = new Spreadsheet();
			    $sheet = $excel->getActiveSheet();
				$sheet->getStyle("A1:Z1")->getFont();
			    $rowCount = sizeof($response['text']) + 1;
			    $sheet->getStyle('A1:eZ'.$rowCount)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
			    $i=2;
			    foreach ($response['text'] as $key => $value) {
			    	if($key === 0){
			    		$sheet->fromArray([$header], NULL, 'A1');	
			    	}
			    	$sheet->fromArray([$value], NULL, 'A'.$i);	
			    	$i++;
			    }
			    header('Content-Type: application/vnd.ms-excel');
			    header('Content-Disposition: attachment;filename="'.$fileName.'"');
			    header('Cache-Control: max-age=0');
			    $writer = new Xlsx($excel);
			    $writer->save('php://output');
			    exit;
			} elseif($response['responseType'] === '2'){
				$_SESSION['message']['warning'] = "Alert! No data available for download.";
				$this->redirect($_SERVER['HTTP_REFERER']);
			} else{
				$_SESSION['message']['alert'] = "Error! Coudn't fetch data, please contact site administrator.";
				$this->redirect($_SERVER['HTTP_REFERER']);
			}
		} else{
			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);
		}
        
    }

    function postImportFormTranslation(){
        $tool_id=$_POST['toolname'];			
        $file=$_FILES['importdata']['tmp_name'];
        $ext = pathinfo($_FILES['importdata']['name'],PATHINFO_EXTENSION);
        if($ext == "xlsx" || $ext == "xls"){
            $spreadsheet =IOFactory::load($file);
            foreach($spreadsheet->getWorksheetIterator() as $worksheet){
                $highestRow = $worksheet->getHighestDataRow();
                $highestColumm = $worksheet->getHighestDataColumn();
                $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumm);
                $translation=array();
                $d=4;
                for($e=0;$e<$highestColumnIndex - 2;$e++){
                    $form_lang_id[]=$worksheet->getCellByColumnAndRow($d,1)->getValue();
                    $d++;
                } 
                $form_id=array();				
                foreach($form_lang_id as $value){
                    $form_id=explode("(",$value);
                    $lid[]=explode(")",$form_id[1])[0];			
                }
                $c=4;
                $i=2;
                for($a=0;$a<$highestRow-1;$a++){
                    $form_id[]=$worksheet->getCellByColumnAndRow(2,$i)->getValue();
                    $form_uid[]=$worksheet->getCellByColumnAndRow(1,$i)->getValue();
                    $que_name[]=$worksheet->getCellByColumnAndRow(3,$i)->getValue();
                    for($b=0;$b<sizeof($lid);$b++){
                        $translation[$c][$i]=$worksheet->getCellByColumnAndRow($c,$i)->getValue();
                        if($translation[$c][$i] != null || $translation[$c][$i] != ""){
                            $data=$this->dbase->addFormLanguage($form_uid[$a],$lid[$b],$translation[$c][$i]);
                        }
                        $c++;
                    }
                    $i++;
                    unset($c);
                    $c=4;
                }
            }
            if($data['responseType'] != '-1'){
                $_SESSION['message']['success'] = "Data uploaded Successfully";
                $this->redirect($_SERVER['HTTP_REFERER']);
            }else{
                $_SESSION['message']['alert'] = "Error! Coudn't update translation";
                $this->redirect($_SERVER['HTTP_REFERER']);
            }
        }else{
            $_SESSION['message']['alert'] = "Error! Coudn't match extension";
            $this->redirect($_SERVER['HTTP_REFERER']);
        }
           
    }

    function getExportQuestionTranslation(){
        if(isset($_POST['exportBtn'])){
			$tool=$this->toolsDBase->displayTool();
			$tool_id=$_POST['toolname'];
			$response = $this->dbase->exportQuestionTranslation($tool_id);
			$header = array_keys($response['text'][0]);
			$fileName = str_replace(' ','_',$_POST['toolName'])."_Questions_".date("d/m/Y").".xlsx";
			if($response['responseType'] === '1'){
				$excel = new Spreadsheet();
			    $sheet = $excel->getActiveSheet();
				$sheet->getStyle("A1:Z1")->getFont();
			    $rowCount = sizeof($response['text']) + 1;
			    $sheet->getStyle('A1:eZ'.$rowCount)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
			    $i=2;
			    foreach ($response['text'] as $key => $value) {
			    	if($key === 0){
			    		$sheet->fromArray([$header], NULL, 'A1');	
			    	}
			    	$sheet->fromArray([$value], NULL, 'A'.$i);	
			    	$i++;
			    }
			    header('Content-Type: application/vnd.ms-excel');
			    header('Content-Disposition: attachment;filename="'.$fileName.'"');
			    header('Cache-Control: max-age=0');
			    $writer = new Xlsx($excel);
			    $writer->save('php://output');
			    exit;
			} elseif($response['responseType'] === '2'){
				$_SESSION['message']['warning'] = "Alert! No data available for download.";
				$this->redirect($_SERVER['HTTP_REFERER']);
			} else{
				$_SESSION['message']['alert'] = "Error! Coudn't fetch data, please contact site administrator.";
				$this->redirect($_SERVER['HTTP_REFERER']);
			}
		} else{
			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);
		}
        
    }

    function postImportQuestionTranslation(){
        $tool_id=$_POST['toolname'];			
        $file=$_FILES['importdata']['tmp_name'];
        $ext = pathinfo($_FILES['importdata']['name'],PATHINFO_EXTENSION);
        if($ext == "xlsx" || $ext == "xls"){
            $spreadsheet =IOFactory::load($file);
            foreach($spreadsheet->getWorksheetIterator() as $worksheet){
                $highestRow = $worksheet->getHighestDataRow();
                $highestColumm = $worksheet->getHighestDataColumn();
                $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumm);
                $translation=array();
                $d=4;
                $f=10;
                for($e=0;$e<$highestColumnIndex - 2;$e++){
                    $que_lang_id[]=$worksheet->getCellByColumnAndRow($d,1)->getValue();
                    $form_lang_id[]=$worksheet->getCellByColumnAndRow($f,1)->getValue();
                    $d++;
                    $f++;
                } 
                $que_id=array();				
                foreach($que_lang_id as $value){
                    $que_id=explode("(",$value);
                    $lid[]=explode(")",$que_id[1])[0];			
                }
                $c=4;
                $i=2;
                for($a=0;$a<$highestRow-1;$a++){
                    $que_uid[]=$worksheet->getCellByColumnAndRow(1,$i)->getValue();
                    $que_id[]=$worksheet->getCellByColumnAndRow(2,$i)->getValue();
                    $que_name[]=$worksheet->getCellByColumnAndRow(3,$i)->getValue();
                    for($b=0;$b<sizeof($lid);$b++){
                        $translation[$c][$i]=$worksheet->getCellByColumnAndRow($c,$i)->getValue();
                        if($translation[$c][$i] != null || $translation[$c][$i] != ""){
                            $data=$this->dbase->addQuestionLanguage($que_uid[$a],$lid[$b],$translation[$c][$i]);
                        }
                        $c++;
                    }
                    $i++;
                    unset($c);
                    $c=4;
                }
            }
            if($data['responseType'] != '-1'){
                $_SESSION['message']['success'] = "Data uploaded Successfully";
                $this->redirect($_SERVER['HTTP_REFERER']);
            }else{
                $_SESSION['message']['alert'] = "Error! Coudn't update translation";
                $this->redirect($_SERVER['HTTP_REFERER']);
            }
        }else{
            $_SESSION['message']['alert'] = "Error! Coudn't match extension";
            $this->redirect($_SERVER['HTTP_REFERER']);
        }
           
    }

    function getExportOptionTranslation(){
        if(isset($_POST['exportBtn'])){
			$tool=$this->toolsDBase->displayTool();
			$tool_id=$_POST['toolname'];
			$response = $this->dbase->exportOptionTranslation();
			$header = array_keys($response['text'][0]);
			$fileName = str_replace(' ','_',$_POST['toolName'])."_Options_".date("d/m/Y").".xlsx";
			if($response['responseType'] === '1'){
				$excel = new Spreadsheet();
			    $sheet = $excel->getActiveSheet();
				$sheet->getStyle("A1:Z1")->getFont();
			    $rowCount = sizeof($response['text']) + 1;
			    $sheet->getStyle('A1:eZ'.$rowCount)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
			    $i=2;
			    foreach ($response['text'] as $key => $value) {
			    	if($key === 0){
			    		$sheet->fromArray([$header], NULL, 'A1');	
			    	}
			    	$sheet->fromArray([$value], NULL, 'A'.$i);	
			    	$i++;
			    }
			    header('Content-Type: application/vnd.ms-excel');
			    header('Content-Disposition: attachment;filename="'.$fileName.'"');
			    header('Cache-Control: max-age=0');
			    $writer = new Xlsx($excel);
			    $writer->save('php://output');
			    exit;
			} elseif($response['responseType'] === '2'){
				$_SESSION['message']['warning'] = "Alert! No data available for download.";
				$this->redirect($_SERVER['HTTP_REFERER']);
			} else{
				$_SESSION['message']['alert'] = "Error! Coudn't fetch data, please contact site administrator.";
				$this->redirect($_SERVER['HTTP_REFERER']);
			}
		} else{
			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);
		}
        
    }

    function postImportOptionTranslation(){
        $tool_id=$_POST['toolname'];			
        $file=$_FILES['importdata']['tmp_name'];
        $ext = pathinfo($_FILES['importdata']['name'],PATHINFO_EXTENSION);
        if($ext == "xlsx" || $ext == "xls"){
            $spreadsheet =IOFactory::load($file);
            foreach($spreadsheet->getWorksheetIterator() as $worksheet){
                $highestRow = $worksheet->getHighestDataRow();
                $highestColumm = $worksheet->getHighestDataColumn();
                $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumm);
                $translation=array();
                $d=4;
                for($e=0;$e<$highestColumnIndex - 2;$e++){
                    $option_lang_id[]=$worksheet->getCellByColumnAndRow($d,1)->getValue();
                    $d++;
                } 
                $option_id=array();				
                foreach($option_lang_id as $value){
                    $option_id=explode("(",$value);
                    $lid[]=explode(")",$option_id[1])[0];			
                }
                $c=4;
                $i=2;
                for($a=0;$a<$highestRow-1;$a++){
                    $option_uid[]=$worksheet->getCellByColumnAndRow(1,$i)->getValue();
                    $option_id[]=$worksheet->getCellByColumnAndRow(2,$i)->getValue();
                    $option_name[]=$worksheet->getCellByColumnAndRow(3,$i)->getValue();
                    for($b=0;$b<sizeof($lid);$b++){
                        $translation[$c][$i]=$worksheet->getCellByColumnAndRow($c,$i)->getValue();
                        if($translation[$c][$i] != null || $translation[$c][$i] != ""){
                            $data=$this->dbase->addOptionLanguage($option_id[$a],$lid[$b],$translation[$c][$i]);
                        }
                        $c++;
                    }
                    $i++;
                    unset($c);
                    $c=4;
                }
            }
            if($data['responseType'] != '-1'){
                $_SESSION['message']['success'] = "Data uploaded Successfully";
                $this->redirect($_SERVER['HTTP_REFERER']);
            }else{
                $_SESSION['message']['alert'] = "Error! Coudn't update translation";
                $this->redirect($_SERVER['HTTP_REFERER']);
            }
        }else{
            $_SESSION['message']['alert'] = "Error! Coudn't match extension";
            $this->redirect($_SERVER['HTTP_REFERER']);
        }
           
    }

    function deleteQuestion(){
        if(isset($_GET['id']) && $_GET['id'] != ""){
    		$response = $this->dbase->deleteQuestion($_GET['id']);
    		if($response['responseType'] === '1'){
    			$_SESSION['message']['success'] = "Question - <b>".$_POST['question']."</b> deleted successfully";
				$this->redirect("/Question/getQuestion");
    		} else{
    			$_SESSION['message']['alert'] = "Coudn't delete Question.";
				$this->redirect($_SERVER['HTTP_REFERER']);
    		}
			
    	} else{
 			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);   		
    	}
    }

}
?>