<?php
namespace FES\GEET\ClassLib;
use FES\GEET\ClassLib\Database;
// Create a new instance of OAuthStore and OAuthServer
// $store = OAuthStore::instance('PDO', array('conn' => $db));
// $server = new OAuthServer();

/**
* Auth
*/
class Auth
{
	private $db;	
	
	function __construct()
	{
		$this->db = new Database;
	}

	static function simpleAuthLogin($data)
	{
		$query = "select user_id, name from users where username = ".$data['username']." and password = ".md5($data['username']);
		echo $query;
	}
}