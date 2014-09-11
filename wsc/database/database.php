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
	
	/**
	 * Gibt einen Datensatz einer Tabelle auf Grund der übergebenen ID zurück.
	 * 
	 * @param string $table	 Zu durchsuchende Tabelle
	 * @param int $id		 Zu suchende ID
	 * @return array $data	 Datensatz der Tabelle
	 */
	public function getDataByID($table, $id)
	{
		$query	= "SELECT * FROM " . $table . " WHERE id = '". $id ."'";
		$result	= $this->query($query) or die("Query: `". $query . "` meldet einen Fehler!<br /><br />" . $this->error);
		$data	= $result->fetch_assoc();
		
		return $data;
	}
	
	/**
	 * Gibt einen Datensatz einer Tabelle auf Grund des übergebenen Wertes und des zu durchsuchendes Feldes zurück.
	 * 
	 * @param string $table	 Zu durchsuchende Tabelle in der Datenbank
	 * @param string $field	 Das zu durchsuchende Feld in $table
	 * @param string $value	 Der gesuchte Inhalt von $field
	 * @return array $data	 Der gesuchte Datensatz
	 */
	public function getDataByField($table, $field, $value)
	{
		$query	= "SELECT * FROM " . $table . " WHERE " . $field . " = '" . $value . "'";
		$result	= $this->query($query) or die("Query: `". $query . "` meldet einen Fehler!<br /><br />" . $this->error);
		$data	= $result->fetch_assoc();
		
		return $data;
	}
	
	public function createRecID()
	{
	    $sql   = "INSERT recIds";
	    $res   = self::query($sql);
	    
	    return $this->insert_id;
	}
}

?>