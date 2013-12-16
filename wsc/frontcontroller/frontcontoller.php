<?php

namespace wsc\frontcontroller;
use wsc\config\config;

/**
 *
 * @author Michi
 *        
 */
class Frontcontroller
{
	
	private $controller;
	private $action;
	private $config;
	private $namespace;
	private $class;
	
	
	/**
	 */
	public function __construct() 
	{
		$this->config	= config::getInstance();
		
		if(isset($_GET['action']))
		{
			$this->action	= $_GET['action'];
		}
		
		if(isset($_GET['site']))
		{
			$this->controller	= $_GET['site'];
		}
		
		$this->namespace	= "controller\\".$this->controller."\\";
		$this->class		= $this->namespace.$this->controller;
		
		
	}
	
	protected function check()
	{
		
	}
	
	
	public function run()
	{
		$controller_path	= $this->config->get("abs_project_path")."/classes/".$this->namespace.$this->controller.".php";
		
		if(file_exists($controller_path))
		{
			if(!class_exists($this->namespace.$this->controller))
			{
				die("Frontcontroller: Der Controller " .$this->controller. " wurde nicht gefunden!(1)<br /> Untersuchter Pfad: ".$controller_path);
			}
			
			$controller	= new $this->class;
			$this->action		= $this->action."_action";
			
			if(in_array($this->action, get_class_methods($controller)))
			{
				$controller->{$this->action}();
			}
			else
			{
				die("Frontcontroller: Die Action " . $this->action . " konnte im Controller `" . $this->controller . "` nicht gefunden werden!");
			}
		}
		else 
		{
			die("Frontcontroller: Der Controller " .$this->controller. " wurde nicht gefunden! (2)<br /> Untersuchter Pfad: ".$controller_path);
		}
	}
}

?>