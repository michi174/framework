<?php

namespace wsc\application;
use wsc\config\config;
use wsc\frontcontroller\Frontcontroller;
use wsc\http\Request\Request;
use wsc\http\response\response;

/**
 * Application (2013 - 12 - 28)
 * 
 * Klasse um eine neue Anwendung zu starten.
 * 
 * @author 		Michael Strasser
 * @name 		Application
 * @version		1.0
 * @copyright	2013 - Michael Strasser
 * @license		Alle Rechte vorbehalten.
 *        
 */
class Application 
{
	private $resources	= array();
	private static $instance	= 0;
	private static $object		= null;
	
	
	public static function getInstance()
	{
		if(self::$object === null)
		{
			self::$object	= new Application();
			
			return self::$object;
		}
		else
		{
			return self::$object;
		}
	}
	private function __clone(){}
	private function __construct()
	{
		if(self::$instance == 0)
		{
			$this->register("Config", config::getInstance());
			$this->checkConfig();
			$this->autostart();
		}
		else
		{
			echo "Achtung! Es wurden mehrere Instanzen der Application initialisiert.";
		}
		
		self::$instance += 1;
	}
	
	/**
	 * Überprüft, ob die Konfiguration für das Framework ausreichend ist.
	 */
	private function checkConfig()
	{
		if(!$this->load("config")->configExists("doc_root"))
		{
			die("Die Einstellung &rsquo;doc_root&rsquo; muss im Config-Objekt vorhanden sein.");
		}
		if(!$this->load("config")->configExists("fw_path"))
		{
			die("Die Einstellung &rsquo;fw_path&rsquo; muss im Config-Objekt vorhanden sein.");
		}
	}
	
	/**
	 * Lädt Ressourcen (Klassen), die automatisch beim start der Application geladen werden sollten.
	 */
	private function autostart()
	{
		$this->register("Request", new Request($this));					//Request muss vor dem FrontController gestartet werden!
		$this->register("Response", new response($this));			
		$this->register("FrontController", new Frontcontroller($this));	//Benötigt ein Request-Objekt in der Applikation.
	}
	
	/**
	 * Fügt der Application eine neue Ressource hinzu. (instanziert ein Objekt einer Klasse)
	 * 
	 * @param string $resource	Name der Ressource (Klasse)
	 * @param resource $object	Objekt der Klasse (Bsp: new Klasse())
	 */
	public function register($resource, $object)
	{
		$this->resources[strtolower($resource)] = $object;
		//echo "<br>".$resource." wurde registriert!<br />";
	}
	
	/**
	 * Gibt das Objekt der angeforderten Klasse zurück.
	 * 
	 * @param string $resource	Name der Ressource
	 * @throws \Exception
	 * @return resource Das instanzierte Objekt der Klasse
	 */
	public function load($resource)
	{
		if(array_key_exists(strtolower($resource), $this->resources))
		{
			return $this->resources[strtolower($resource)];
		}
		else
		{
			throw new \Exception("Die Ressource '".$resource."' wurde in der Application wurde nicht registriert.");
		}
	}
	
	/**
	 * Führt die Anwendung aus.
	 */
	public function run()
	{
		/*TODO:
		//In Zukunft sollte dieser Teil so aussehen:
		//-----------------------------------------------------------------------------------
		//$response = frontroller->run()	//Controller und Action werden übergeben.
		//$response -> setHeader(); ...	//Ausgabe kann noch manipuliert werden.
		//$response -> send(); 				//Ausgabe wird gestartet.
		
		//Bis das möglich ist, wird eine der Frontcontroller einfach ausgeführt...
		//-----------------------------------------------------------------------------------
		 */
		$this->load("FrontController")->run();
		
	}
}

?>