<?php 
namespace FES\GEET\ClassLib;
use FES\GEET\ClassLib\GeetMain;
use FES\GEET\ClassLib\ClassDBase\ImportDBase;
use FES\GEET\ClassLib\ClassDBase\ToolsDBase;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Import extends GeetMain{
    private $dbase;
	private $toolsDBase;
	protected $iofactory;
    function __construct($parent = true){
		if($parent){
			parent::__construct();
			parent::__addAction(array("User.login","User.getLogout","User.register"));
		}
		$this->dbase = new ImportDBase;
		$this->toolsDBase = new ToolsDBase;
	}

    function getImport(){
		$tool=$this->toolsDBase->displayTool();
		include __DIR__."/../Views/Import/importTemp.php";
    }

	function postImportExport(){
		
		if(isset($_POST['exportLt'])){			
			switch($_POST['exportLt']){
				case 'exportLabel':
					$this->getToolLabel();
					break;
				case 'exportTranslation':
					$this->getLabelTranslation();
					break;
			}
		}else if(isset($_POST['importLt'])){			
			switch($_POST['importLt']){
				case 'importLabel':
					$this->postLabel();
					break;
				case 'importTranslation':
					$this->postLabelTranslation();
					break;
			}
		}else{}
	}

	function getToolLabel(){
		if(isset($_POST['exportBtn']) && isset($_POST['toolname'])){
			$tool=$this->toolsDBase->displayTool();
			$tool_id=$_POST['toolname'];
			$response = $this->dbase->exportToolLabel($tool_id);
			$header = array_keys($response['text'][0]);
			$fileName = "Label_of_".str_replace(' ','_',$_POST['toolName'])."_".date("d/m/Y").".xlsx";
			if($response['responseType'] === '1'){
				$excel = new Spreadsheet();
			    $sheet = $excel->getActiveSheet();
				$sheet->getStyle("A1:EZ1")->getFont();
			    $rowCount = sizeof($response['text']) + 1;
			    $sheet->getStyle('A1:EZ'.$rowCount)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
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

	function getLabelTranslation(){
		if(isset($_POST['exportBtn']) && isset($_POST['toolname'])){
			$tool_id=$_POST['toolname'];
			$response = $this->dbase->exportLabelTranslation($tool_id);	
			$header = array_keys($response['text'][0]);
			$fileName = "Translation_of_".str_replace(' ','_',$_POST['toolName'])."_".date("d/m/Y").".xlsx";
			if($response['responseType'] === '1'){
				$excel = new Spreadsheet();
			    $sheet = $excel->getActiveSheet();
			    $sheet->getStyle("A1:M1")->getFont();
			    $rowCount = sizeof($response['text']) + 1;
				$columnCount = sizeof($header);
			    $sheet->getStyle($rowCount)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
				$sheet->getStyle($columnCount)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
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

	function postLabel(){

		if (isset($_POST['importBtn']) && isset($_POST['toolname'])) {
			$tool_id=$_POST['toolname'];
			$file=$_FILES['importdata']['tmp_name'];
			$ext = pathinfo($_FILES['importdata']['name'],PATHINFO_EXTENSION);
			if($ext == "xlsx" || $ext == "xls"){
				$spreadsheet =IOFactory::load($file);
				$labelname=array();
				foreach($spreadsheet->getWorksheetIterator() as $worksheet){
					$highestRow = $worksheet->getHighestRow();				
					for($i=2;$i<=$highestRow;$i++){
						$labelname[]=$worksheet->getCellByColumnAndRow(3,$i)->getValue();
						$labelid[]=$worksheet->getCellByColumnAndRow(2,$i)->getValue();
					}
					for($j=0;$j<sizeof($labelname);$j++){
						if($labelname[$j] != null || $labelname[$j] != ""){
							$response=$this->dbase->addLabel($labelname[$j],$labelid[$j]);
							$label_id=$response['text'][0]['id'];
							if($response['responseType'] === '1'){
								$relation=$this->dbase->addToolLabel($tool_id,$label_id);
							}
						}else{
							$deletelabel=$this->dbase->deleteLabel($labelid[$j]);
							$deletetoollabel=$this->dbase->deleteToolLabel($tool_id,$labelid[$j]);
						}
					}	
				}
				if($response['responseType'] != '-1'){
					$_SESSION['message']['success'] = "Data uploaded Successfully";
					$this->redirect($_SERVER['HTTP_REFERER']);
				}else{
					$_SESSION['message']['alert'] = "Error! Coudn't update data";
					$this->redirect($_SERVER['HTTP_REFERER']);
				}
			}else{
				$_SESSION['message']['alert'] = "Error! Coudn't match extension";
				$this->redirect($_SERVER['HTTP_REFERER']);
			}
		}else{
			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);
		}

	}

	function postLabelTranslation(){
		if(isset($_POST['importBtn']) && isset($_POST['toolname'])) {
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
						$lang_id[]=$worksheet->getCellByColumnAndRow($d,1)->getValue();
						$d++;
					}		
					$id=array();					
					foreach($lang_id as $value){
						$id=explode("(",$value);
						$lid[]=explode(")",$id[1])[0];			
					}
					$c=4;
					$i=2;
					for($a=0;$a<$highestRow-1;$a++){
						$labelid[]=$worksheet->getCellByColumnAndRow(2,$i)->getValue();
						$labelname[]=$worksheet->getCellByColumnAndRow(3,$i)->getValue();
						
						if($labelname[$a] != null || $labelname[$a] != ""){
							$response=$this->dbase->addLabel($labelname[$a],$labelid[$a]);
							$label_id=$response['text'][0]['id'];
							if($response['responseType'] === '1'){
								$relation=$this->dbase->addToolLabel($tool_id,$label_id);
							}
						}else{
							$deletelabel=$this->dbase->deleteLabel($labelid[$a]);
							$deletetoollabel=$this->dbase->deleteToolLabel($tool_id,$labelid[$a]);
						}
						for($b=0;$b<sizeof($lid);$b++){
							$translation[$c][$i]=$worksheet->getCellByColumnAndRow($c,$i)->getValue();
							if($response['responseType'] === '1'){
								if($translation[$c][$i] != null || $translation[$c][$i] != ""){
									$data=$this->dbase->addTranslation($label_id,$lid[$b],$translation[$c][$i]);
								}else{
									$data=$this->dbase->deleteTranslation($label_id,$lid[$b]);
								}
							}else{
								if($translation[$c][$i] != null || $translation[$c][$i] != ""){
									$data=$this->dbase->addTranslation($labelid[$a],$lid[$b],$translation[$c][$i]);
								}else{
									$data=$this->dbase->deleteTranslation($labelid[$a],$lid[$b]);
								}
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
		}else{
			$_SESSION['message']['alert'] = "Error: missing parameter(s)";
			$this->redirect($_SERVER['HTTP_REFERER']);
		}

	}

	
}
?>