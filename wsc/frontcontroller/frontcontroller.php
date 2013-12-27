<?php

namespace wsc\frontcontroller;
use wsc\config\Config;
use wsc\http\Request\Request;
use wsc\application\Application;
use wsc\controller\controller_abstract;
use wsc\autoload\Autoload;
use wsc\controller\Subcontroller_abstract;

/**
 *
 * Request (2013 - 12 - 16)
 * 
 * Der Frontcontroller ruft den zum Request gehörenden Controller auf.
 * 
 * @author 		Michael Strasser
 * @name 		Frontcontroller
 * @version		1.0
 * @copyright	2013 - Michael Strasser
 * @license		Alle Rechte vorbehalten.
 *        
 */
class Frontcontroller
{
	const ACTION_SUFFIX	= "_action";
	
	private $controller;
	private $action;
	
	private $subControllers	= array();
	
	private $namespace	= NULL;
	private $class		= NULL;
	
	private $config;
	private $application;
	
	
	public function __construct(Application &$application)
	{
		$this->application	= $application;
		$this->config		= $this->application->load("config");

		$this->controller	= $this->application->load("request")->getController();
		$this->action		= $this->application->load("request")->getAction();
		
		$this->formClassName();
		$this->route();

	}
	
	/**
	 * Legt den benötigten Controller und die benötigte Action fest.
	 */
	private function route()
	{
		if(!$this->isController())
		{
			//DEBUG: echo "Controller &rsquo;".$this->controller."&rsquo; wurde nicht gefunden. Der Standardcontroller &rsquo;" . $this->config->get("default_controller"). "&rsquo; wird geladen.<br />";
			$this->controller = $this->config->get("default_controller");
		}
		
		if(!$this->isAction())
		{
			//DEBUG: echo "Die Action &rsquo;".$this->action."&rsquo; wurde im Controller &rsquo;".$this->controller."&rsquo; nicht gefunden. Es wird die Standard-Action geladen.<br />";
			$this->action	= "default";
		}
			//DEBUG: echo "Es wurde &rsquo;" . $this->controller ."->" . $this->action ."&rsquo; geladen!<br />";
	}
	
	/**
	 * Ruft den benötigten Controller samt SubController auf.
	 */
	public function run()
	{
		$this->formClassName();
		
		$this->runSubControllers(true);
		
		$controller	= new $this->class($this->application);
		if(controller_abstract::isValidController($controller))
		{
			$controller->{$this->action.self::ACTION_SUFFIX}();
			
			$this->runSubControllers(false);
		}
		else
		{
			die("Es wurde ein unzulaessiger Controller '".$this->controller."' ermittelt. Die Application wird beendet...");
		}
	}
	
	
	/**
	 * Fügt einen SubController hinzu.
	 * 
	 * Soll der SubController für bestimmte MainContoller nicht ausgeführt werden,
	 * kann der Name des MainControllers als Arrayelement in die Blacklist geschrieben werden.
	 * 
	 * @param string 	$subcontroller	Name des SubControllers
	 * @param array 	$blacklist		Optional: Blackliste für MainController
	 */
	public function addSubController($subcontroller, $blacklist = array())
	{
		$this->subControllers[$subcontroller]	= $blacklist;
	}
	
	
	/**
	 * Führt die SubController aus.
	 * 
	 * Über den Paramenter $beforeMainController wird gesteuert, ob der SubController vor
	 * oder nach dem MainController ausgeführt wird.
	 * 
	 * Vorher 	= true
	 * Nachher 	= false
	 * 
	 * @param boolean $beforeMainController	Zeitpunkt zudem der SubController ausgeührt wird.
	 */
	private function runSubControllers($beforeMainController)
	{
		foreach($this->subControllers as $subController	=> $blacklist)
		{
			//Steht der MainController auf der Blacklist des SubControllers?
			if(!in_array($this->controller, $blacklist))
			{
				//Nein, er wird bearbeitet.
				$subcontroller_class	= $this->application->load("config")->get("subcontroller_namespace")."\\".$subController."\\".$subController;
				
				//Existiert der SubController?
				if(class_exists($subcontroller_class))
				{
					$object	= new $subcontroller_class;
					//Ist der SubController gültig?
					if(Subcontroller_abstract::isValidSubController($object))
					{
						if($beforeMainController === true)
						{
							//Alle SubController die vor dem MainConroller ausgeführt werden müssen, werden gestartet.
							if(method_exists($object, "runBeforeMain"))
							{
								$object->runBeforeMain();
								
								//Gibt es noch Funktionen, die dannach ausgeführt werden müssen?
								if(!method_exists($object, "runAfterMain"))
								{
									//Nein, dann enfernen wir den SubController
									unset($this->subControllers[$subController]);
								}
							}
							else
							{
								//DEBUG:echo "Der SubController '".$subController."' kann nicht vor dem MainController ausgefuehrt werden, da keine runBeforMain-Methode vorhanden ist.<br />";
							}
						}
						else
						{
							if(method_exists($object, "runAfterMain"))
							{
								$object->runAfterMain();
								unset($this->subControllers[$subController]);
							}
							else
							{
								//DEBUG:echo "Der SubController '".$subController."' kann nicht vor dem MainController ausgefuehrt werden, da keine runBeforMain-Methode vorhanden ist.<br />";
							}
						}
					}
					else
					{
						die("Es wurde ein unzulaessiger SubController registriert: '".$subController."' - die Application wird beendet...<br />");
					}
				}
				else
				{
					die("SubController '".$subController."' wurde nicht gefunden (Klasse).<br />");
				}
			}
			else
			{
				echo "Der Controller '".$this->controller."' steht auf der Blackliste des SubControllers '".$subController."' und wird deshalb nicht ausgefuehrt.<br />";
			}
		}
	}
	
	
	/**
	 * Überprüft, ob das Request Objekt einen gültigen Controller beinhaltet.
	 * 
	 * @return boolean
	 */
	private function isController()
	{
		$controller_path	= $this->config->get("abs_project_path") ."/".$this->config->get("class_dir")."/". $this->class .".php";
		
		if(file_exists($controller_path))
		{
			if(!class_exists($this->class))
			{
				return false;
			}
		}
		else
		{
			return false;
		}
		
		return true;
	}
	
	
	/**
	 * Überprüft, ob das Request Objekt eine gültige Action beinhaltet.
	 *
	 * @return boolean
	 */
	private function isAction()
	{
		if($this->isController($this->controller))
		{
			if(in_array($this->action.self::ACTION_SUFFIX, get_class_methods($this->class)))
			{
				return true;
			}
		}
		return false;
	}
	
	
	/**
	 * Formt den Klassennamen durch den Controller und den in der Config enthaltenen Namespace.
	 */
	private function formClassName()
	{
		$this->namespace	= $this->config->get("controller_namespace")."\\".$this->controller."\\";
		$this->class		= $this->namespace.$this->controller;
	}
}
?>