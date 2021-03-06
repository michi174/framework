<?php

namespace wsc\frontcontroller;

use wsc\controller\controller_abstract;
use wsc\controller\Subcontroller_abstract;
use wsc\functions\tools\Tools;
use wsc\application\Application;

/**
 *
 * Request (2013 - 12 - 16)
 * 
 * Der Frontcontroller ruft den zum Request geh�renden Controller auf und verarbeitet die SubController.
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
	const ACTION_SUFFIX    = "_action";
	
	private $controller    = NULL;
	private $action        = NULL;
	private $modul         = NULL;
	
	private $active_controller;
	private $active_action;
	private $active_modul;
	
	private $subControllers    = array();
	private $subModuls         = array();
	
	private $namespace = NULL;
	private $class     = NULL;
	
	private $config;
	private $application;
	
	
	public function __construct(Application $application)
	{
		if($application	instanceof Application)
		{
			$this->application	= $application;
		}
		
		$this->config     = $this->application->load("config");
		$this->controller = $this->application->load("request")->getController();
		$this->action     = $this->application->load("request")->getAction();
		$this->modul      = $this->application->load("request")->getModule();
		
		$this->formClassName();
		$this->route();

	}
	
	/**
	 * Ruft den ben�tigten Controller samt SubController auf.
	 */
	public function run()
	{
		$this->formClassName();
		
		$controller	= new $this->class();
		
		$acl		= $this->application->load("Acl");
		$user		= $this->application->load("Auth")->getUser();
		
		//SubController vor MainController ausf�hren.
		$this->runSubControllers(true);

		if(controller_abstract::isValidController($controller))
		{
			//Berechtigung �berpr�fen
			if($acl->hasPermission($user, $this->controller, $this->action))
			{
				//MainController ausf�hren
				$this->setActiveController($this->controller);
				$this->setActiveAction($this->action);
				$controller->{$this->action.self::ACTION_SUFFIX}();
				$controller->sendView();
			}
			else 
			{
				Tools::internalRedirect("error", "nopermission", array("next" => urlencode($_SERVER['QUERY_STRING'])));
			}
		}
		else
		{
			die("Es wurde ein unzulaessiger Controller '".$this->controller."' ermittelt. Die Application wird beendet...");
		}
		
		//SubController nach MainController ausf�hren.
		$this->runSubControllers(false);
		
		$this->application->load("response")->send();
	}
	
	
	/**
	 * F�gt einen SubController hinzu.
	 * 
	 * Soll der SubController f�r bestimmte MainContoller nicht ausgef�hrt werden,
	 * kann der Name des MainControllers als Arrayelement in die Blacklist geschrieben werden.
	 * 
	 * @param string 	$subcontroller	Name des SubControllers
	 * @param array 	$blacklist		Optional: Blackliste f�r MainController
	 */
	public function addSubController($subcontroller, $blacklist = array())
	{
		$this->subControllers[$subcontroller]	= $blacklist;
	}
	
	/**
	 * Gibt den verwendetet Controller zur�ck
	 *
	 * @return string $controller	Der aktive Controller
	 */
	public function getActiveController()
	{
		return $this->active_controller;
	}
	
	/**
	 * Gibt die verwendete Action zur�ck
	 *
	 * @return string $action	Die aktive Action
	 */
	public function getActiveAction()
	{
		return $this->active_action;
	}
	
	/**
	 * Legt den ben�tigten Controller und die ben�tigte Action fest.
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
	 * F�hrt die SubController aus.
	 *
	 * �ber den Paramenter $beforeMainController wird gesteuert, ob der SubController vor
	 * oder nach dem MainController ausgef�hrt wird.
	 *
	 * Vorher 	= true
	 * Nachher 	= false
	 *
	 * @param boolean $beforeMainController	Zeitpunkt zudem der SubController ausge�hrt wird.
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
					//Ist der SubController g�ltig?
					if(Subcontroller_abstract::isValidSubController($object))
					{
						$this->setActiveController($subController);
						$this->setActiveAction($subController);
	
						if($beforeMainController === true)
						{
							//Alle SubController die vor dem MainConroller ausgef�hrt werden m�ssen, werden gestartet.
							if(method_exists($object, "runBeforeMain"))
							{
								$object->runBeforeMain();
								$object->sendView();
	
								//Gibt es noch Funktionen, die dannach ausgef�hrt werden m�ssen?
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
								$object->sendView();
	
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
					die("SubController '".$subController.$subcontroller_class."' wurde nicht gefunden (Klasse).<br />");
				}
			}
			else
			{
				//DEBUG:echo "Der Controller '".$this->controller."' steht auf der Blackliste des SubControllers '".$subController."' und wird deshalb nicht ausgefuehrt.<br />";
			}
		}
	}
	
	/**
	 * Setzt den Namen des aktiven Controllers
	 * 
	 * @param string $controller	Name des Controllers
	 */
	private function setActiveController($controller)
	{
		$this->active_controller	= $controller;
	}
	
	/**
	 * Setzt den Namen der aktiven Action
	 *
	 * @param string $action	Name der Action
	 */
	private function setActiveAction($action)
	{
		$this->active_action	= $action;
	}
	
	/**
	 * �berpr�ft, ob das Request Objekt einen g�ltigen Controller beinhaltet.
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
	 * �berpr�ft, ob das Request Objekt eine g�ltige Action beinhaltet.
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