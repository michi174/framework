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
final class Config 
{
	private static $object	= NULL;
	private $configuration	= array();
	
	
	/**
	 * Gibt das Objekt der Konfiguration zur�ck.
	 * Singleton Methode damit die Config nicht versehentlich �berschrieben werden kann.
	 * 
	 * @return object $config	| Objekt der Konfiguration
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
	
	
	/**
	 * Lie�t eine Konfigurationsdatei aus und schreibt diese automatisch in die Config-Klasse.
	 * 
	 * @param string $path
	 */
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
	
	
	/**
	 * Schreibt eine neue Eigenschaft in die Konfiguration.
	 * 
	 * @param string $config	| Name der Eigenschaft
	 * @param string $value		| Inhalt der Eigenschaft
	 */
	public function set($config, $value)
	{
		$this->configuration[strtolower($config)]	= $value;
	}
	
	
	/**
	 * Gibt den Wert einer Eigenschaft der Konfiguration zur�ck.
	 * Wird die Eigenschaft nicht gefunden, wird false zur�ckgegeben.
	 * 
	 * @param string $config	| Name der Eigenschaft
	 * @return string config | boolean
	 */
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
	
	
	/**
	 * Pr�ft, ob eine Eigenschaft in der Konfiguration exisitert.
	 * 
	 * @param string $config	| Name der Eigenschaft
	 * @return boolean
	 */
	public function configExists($config)
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