<?php
namespace FES\GEET\ClassLib;
// use Nette\Database\Connection;
// use Nette\Database\Context;
// use Nette\Database\Table;


class Database
{
	protected $activeConnection;
	protected $activeDatabase;
	protected $host;
	protected $dbname;
	protected $port;
	protected $dsn;
	protected $user;
	protected $password;

	protected function __construct($dbName = ''){		
		$this->host = "localhost";
		$this->dbname = "postgres";
		$this->port = '5432';
		$this->user = "postgres";
		$this->password = "postgres";
		$this->dsn="pgsql:host=".$this->host.";dbname=".$this->dbname;
	}
	
	protected function coreConnection(){
		try{
			$dbh = null;
			$dbh = new \PDO($this->dsn, $this->user, $this->password);
			$dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			if($dbh){
				return $dbh;
			}
			return false;
		}catch(\PDOException $e){
			error_log($e->getMessage());
			echo $error = $e->getMessage(); 
			echo '<div class="messages"><div class="callout warning"><b>Error:</b> Failed to connect iodashboard database.</div></div>';
			return false;
		}
	}

	protected function BigDB(){	
		try{
			$dbh = null;
			$dbh = new \PDO($this->dsn, $this->user, $this->password);
			$dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			if($dbh){
				return $dbh;
			}
			return false;
		}catch(\PDOException $e){
			error_log($e->getMessage());
			echo $error = $e->getMessage(); 
			echo '<div class="messages"><div class="callout warning"><b>Error:</b> Failed to connect bigdata database.</div></div>';
			return false;
		}
	}

	protected function geetDB(){	
		try{
			$dbh = null;
			$dbh = new \PDO($this->dsn, $this->user, $this->password);
			$dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			if($dbh){
				return $dbh;
			}
			return false;
		}catch(\PDOException $e){
			error_log($e->getMessage());
			echo $error = $e->getMessage(); 
			echo '<div class="messages"><div class="callout warning"><b>Error:</b> Failed to connect geetdb database.</div></div>';
			return false;
		}
	}


	protected function clartDB(){	
		//echo "Inside clartdb method ".$this->dsn."<br>";
		try{
			$dbh = null;
			$dbh = new \PDO($this->dsn, $this->user, $this->password);
			$dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			if($dbh){
				return $dbh;
			}
			return false;
		}catch(\PDOException $e){
			error_log($e->getMessage());
			echo $error = $e->getMessage(); 
			echo '<div class="messages"><div class="callout warning"><b>Error:</b> Failed to connect clartdb database.</div></div>';
			return false;
		}
	}

	protected function clartDetDB(){	
		//echo "Inside clartdet method ".$this->dsn."<br>";
		try{
			$dbh = null;
			$dbh = new \PDO($this->dsn, $this->user, $this->password);
			$dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			if($dbh){
				return $dbh;
			}
			return false;
		}catch(\PDOException $e){
			error_log($e->getMessage());
			echo $error = $e->getMessage(); 
			echo '<div class="messages"><div class="callout warning"><b>Error:</b> Failed to connect clartdb database.</div></div>';
			return false;
		}
	}

	protected function clmDB(){	
		//echo "Inside clartdet method ".$this->dsn."<br>";
		try{
			$dbh = null;
			$dbh = new \PDO($this->dsn, $this->user, $this->password);
			$dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			if($dbh){
				return $dbh;
			}
			return false;
		}catch(\PDOException $e){
			error_log($e->getMessage());
			echo $error = $e->getMessage(); 
			echo '<div class="messages"><div class="callout warning"><b>Error:</b> Failed to connect clm database.</div></div>';
			return false;
		}
	}

	protected function cwbDB(){	
		//echo "Inside clartdet method ".$this->dsn."<br>";
		try{
			$dbh = null;
			$dbh = new \PDO($this->dsn, $this->user, $this->password);
			$dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			if($dbh){
				return $dbh;
			}
			return false;
		}catch(\PDOException $e){
			error_log($e->getMessage());
			echo $error = $e->getMessage(); 
			echo '<div class="messages"><div class="callout warning"><b>Error:</b> Failed to connect cwb database.</div></div>';
			return false;
		}
	}

	public function getFields($table_name, $columns){
		if(is_null($columns))
		{
			$query = "select column_name from information_schema.columns where table_name='user_master';";	
		} else{
			if(is_array($columns) && (count($columns) == count($columns, COUNT_RECURSIVE)))
			{
				$query = "select ".implode(",", $columns)." from information_schema.columns where table_name='user_master';";	
			}
		}
		$response = $this->coreConnection($query);
		return $response;
	}
	
	function validateGETPOST($data){
		array_walk_recursive($data,[$this,'validateUserInputs']);
		return $data;
	}
	
	function validateUserInputs(&$item, $key){
		$this->dsn="pgsql:host=$this->host;dbname=$this->dbname";
		try{
			$conn = pg_pconnect("host=".$this->host." port=".$this->port." dbname=".$this->dbname." user=".$this->user." password=".$this->password);
			$item = pg_escape_string($item);
			if($key != 'expr' && $item != '==' && $item != '!=' && $item != '<=' && $item != '>=' && $item != '>' && $item != '<'){ // To prevent striping of rellational operators used in scheme's criteria
				$item = strip_tags($item);
			}
		} catch (Exception $e){
			$_SESSION['message']['warning'] = 'Error: '.$e->getMessage();
		}
	}
}

