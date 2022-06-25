<?php
namespace FES\GEET\ClassLib\ClassDBase;
use FES\GEET\ClassLib\Database;

/**
* Scheme class
*/
class AdminHierarchyDBase extends Database
{
	protected $database;

	function __construct(){
		$this->database = new Database('iodashboard');
	}

	function index(){
		  $this->rest->post(json_encode(array('value')));
	}

}