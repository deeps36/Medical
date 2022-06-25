<?php 
namespace FES\GEET\ClassLib;
use FES\GEET\ClassLib\InterfacesLibrary\ActionInterface;
/**
* 
*/
class Action implements ActionInterface
{
	function __construct()
	{
		
	}

	function register($action)
	{
		if(isset($_SESSION['actions'])){
			if(!in_array($action, $_SESSION['actions']))
			{
				$_SESSION['actions'][] = $action;
			}
		} else{
			$_SESSION['actions'][] = $action;
		}
	}

	function unregister($action)
	{
		// add to session 	
		$key = array_search ($action, $_SESSION['actions']);
		unset($_SESSION['actions'][$key]);
	}

	function listall()
	{
		// add to session 
		return json_encode($_SESSION['actions']);
	}


	function __registerMethods($method, $class)
	{
		//$this->register($class, $method);
	}
}