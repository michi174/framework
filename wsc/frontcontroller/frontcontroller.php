<?php

namespace wsc\frontcontroller;
use wsc\config\config;
use wsc\http\Request\Request;

/**
 *
 * @author Michi
 *        
 */
class Frontcontroller
{
	const ACTION_SUFFIX	= "_action";
	
	private $controller;
	private $action;
	
	private $namespace	= NULL;
	private $class		= NULL;
	
	private $config;
	
	
	public function __construct(Request $request) 
	{
		if($request instanceof Request)
		{
			$this->config		= config::getInstance();
			
			$this->controller	= $request->getController();
			$this->action		= $request->getAction();
			
			$this->formClassName();
			$this->route();
		}
		else
		{
			die("Fehler: Der Frontcontroller hat kein gültiges Requestobjekt erhalten. Die Application wurde beendet...");
		}
	}
	
	private function route()
	{
		if(!$this->isController())
		{
			echo "Controller &rsquo;".$this->controller."&rsquo; wurde nicht gefunden. Der Standardcontroller &rsquo;" . $this->config->get("default_controller"). "&rsquo; wird geladen.<br />";
			$this->controller = $this->config->get("default_controller");
		}
		
		if(!$this->isAction())
		{
			echo "Die Action &rsquo;".$this->action."&rsquo; wurde im Controller &rsquo;".$this->controller."&rsquo; nicht gefunden. Es wird die Standard-Action geladen.<br />";
			
			$this->action	= "default";
		}
		
		echo "Es wurde &rsquo;" . $this->controller ."->" . $this->action ."&rsquo; geladen!<br />";
	}
	
	/**
	 * Ruft den benötigten Controller auf.
	 */
	public function run()
	{
		$this->formClassName();
	
		$controller	= new $this->class;
		$controller->{$this->action.self::ACTION_SUFFIX}();
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
			if(in_array($this->action.self::ACTION_SUFFIX, get_class_methods($this->namespace.$this->controller)))
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