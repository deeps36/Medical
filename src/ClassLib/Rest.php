<?php
namespace FES\GEET\ClassLib;
use FES\GEET\ClassLib\InterfacesLibrary\RestInterface;
use FES\GEET\ClassLib\Action;
/**
* Rest api
*/
class Rest implements RestInterface
{
	private $params;
	private $actionList;
	
	function __construct()
	{
		$action = new Action;
		$this->actionList =  $action->listall();
	}

	function get()
	{
			
	}

	function post($params, $mobile = false)
	{
		if(isset($_SERVER['REDIRECT_URL'])){
			$uri = explode("/", $_SERVER['REDIRECT_URL']);
			array_shift($uri);
			//print_r($uri);
			if($mobile == "true"){
				echo $params;
			} else{
				//echo $params; //for web service request
				return $params;
			}
		} else{
			//echo $params;
			//$this->params = $params;
			return $params;
			
		}
	}

	function put($params)
	{
		
	}

	function delete()
	{
		
	}

	function is_rest($uri)
	{
		$action = ucfirst($uri[0]).".".$uri[1];
		$hayStack = json_decode($this->actionList, true);
		//var_dump($hayStack);
		if(in_array($action, $hayStack))
		{
			return true;	
		}
		return false;
	}

}