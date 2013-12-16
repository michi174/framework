<?php
namespace wsc\database;

use wsc\config\config;
class Database extends \mysqli
{
	private static $object = NULL;
	
	public function __construct($host, $user, $pass, $database)
	{
		$this->config = config::getInstance();
		
		parent::__construct($host, $user, $pass, $database);
	}
	
	public static function getInstance()
	{
		if(self::$object instanceof \wsc\config\Config)
		{
			return self::$object;
		}
		else
		{
			$config			= config::getInstance();
			
			$object			= new Database($config->get("database_host"), $config->get("database_user"), $config->get("database_password"), $config->get("database"));
			self::$object	= $object;
	
			return $object;
		}
	}
	
	public function getDataByID($table, $id)
	{
		$query	= "SELECT * FROM " . $table . " WHERE id = '". $id ."'";
		$result	= $this->query($query) or die("Query: `". $query . "` meldet einen Fehler!<br /><br />" . $this->error);
		$data	= $result->fetch_assoc();
		
		return $data;
	}
	
	public function getDataByField($table, $field, $value)
	{
		$query	= "SELECT * FROM " . $table . " WHERE " . $field . " = '" . $value . "'";
		$result	= $this->query($query) or die("Query: `". $query . "` meldet einen Fehler!<br /><br />" . $this->error);
		$data	= $result->fetch_assoc();
		
		return $data;
	}
}

?>