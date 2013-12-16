<?php

namespace wsc\config;

/**
 * Config (2013 - 05 - 31)
 * 
 * Konfiguriert websitespezifische Einstellungen.
 *
 * @name Config
 * @version 1.0
 * @author Michi
 * @copyright 2013 - Michael Strasser. Alle Rechte vorbehalten.
 *        
 */
final class config 
{
	private static $object	= NULL;
	
	private $configuration	= array();
	
	
	/**
	 * Singleton Methode damit die Config nicht versehentlich überschrieben werden kann.
	 * 
	 * @return object $config
	 */
	private function __construct(){}
	private function __clone(){}
	public static function getInstance()
	{
		if(self::$object instanceof Config)
		{
			return self::$object;
		}
		else
		{
			$object			= new Config();
			self::$object	= $object;
				
			return $object;
		}
	}
	
	public function readIniFile($path)
	{
		if(file_exists($path))
		{
			$ini = parse_ini_file($path);
			foreach($ini as $key => $value)
			{
				$this->set($key, $value);
			}
		}
		else
		{
			die("Datei ".$path." wurde nicht gefunden");
		}
	}
	
	public function set($config, $value)
	{
		$this->configuration[strtolower($config)]	= $value;
	}
	
	public function get($config)
	{
		if($this->configExists($config))
		{
			return $this->configuration[strtolower($config)];
		}
		else 
		{
			return false;
		}
	}
	
	private function configExists($config)
	{
		if(array_key_exists(strtolower($config), $this->configuration))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}

?>