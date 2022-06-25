<?php
namespace FES\GEET\ClassLib;
use FES\GEET\ClassLib\GeetMain;
use FES\GEET\ClassLib\ClassDBase\AdminHierarchyDBase;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use FES\GEET\ClassLib\ClassDBase\UserDBase;

/**
* DataEntry class
*/
class AdminHierarchy extends GeetMain{

	protected $dbase;
	private $rest;
    protected $sms;
    
    function __construct($parent = true)
	{
		if($parent){
			parent::__construct();
			parent::__addAction(array());
		}
		$this->dbase = new AdminHierarchyDBase;
		$this->userDbase = new UserDBase;
	}
}

	