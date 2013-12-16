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
	
	private $namespace = NULL;
	private $class;
	
	private $config;
	
	
	/**
	 */
	public function __construct() 
	{
		$this->config		= config::getInstance();
	}
	
	
	public function init(Request $request)
	{
		$controller	= $request->getController();
		$action		= $request->getAction();
		
		$this->namespace	= $this->config->get("controller_namespace")."\\".$controller."\\";
		
		if($this->isController($controller))
		{
			$this->controller = $controller;
			
			if($this->isAction($action, $controller))
			{
				$this->action = $action;
			}
			else
			{
				echo "Die Action '".$action."' wurde im Controller '".$controller."' nicht gefunden. Es wurde die Standard-Action geladen.<br />";
				
				$this->action	= "default";
			}
		}
		else
		{
			die("Controller wurde nicht gefunden");
		}
			
	}
	
	private function isController($controller)
	{
		//Namespace evtl. in der Config festlegen?
		
		$namespace			= $this->namespace;
		$class				= $namespace.$controller;
		$controller_path	= $this->config->get("abs_project_path") ."/".$this->config->get("class_dir")."/". $class .".php";
		
		if(file_exists($controller_path))
		{
			if(!class_exists($class))
			{
				echo "Frontcontroller: Der Controller " .$controller. " wurde nicht gefunden!(Klasse)";
				return false;
			}
		}
		else
		{
			echo "Frontcontroller: Der Controller " .$controller. " wurde nicht gefunden! (Datei)<br /> Untersuchter Pfad: ".$controller_path;
			return false;
		}
		
		return true;
	}
	
	private function isAction($action, $controller)
	{
		if($this->isController($controller))
		{
			if(in_array($action."_action", get_class_methods($this->namespace.$controller)))
			{
				return true;
			}
		}
		return false;
	}
	
	
	public function run()
	{
		$controller	= new $this->class;
		$controller->{$this->action}();
	}
}

?>