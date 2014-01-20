<?php

namespace wsc\view;
use wsc\application\Application;
/**
 *
 * @author Michi
 *        
 */
abstract class View_abstract 
{
	protected 	$application;
	private 	$is_subcontroller;
	protected	$template_content;
	public	 	$variables	= array();
	protected	$renderer 	= array(
		'extension'	=> 'php',
		'name'		=> 'PHP'
	);
	
	
	public function __construct($is_subcontroller = false)
	{
		$this->application = Application::getInstance();
		$this->is_subcontroller	= $is_subcontroller;
	}
	
	
	/**
	 * Muss von jeder abgeleitenen View Klasse implementiert werden.
	 *
	 * Gibt die fertige View zurück.
	 */
	abstract public function render($content);
	
	public function assign($vars, $value = "")
	{
		if(!is_array($vars))
		{
			$this->variables[$vars] = $value;
		}
	
		if(is_array($vars))
		{
			foreach ($vars as $var	=> $value)
			{
				$this->variables[$var]	= $value;
			}
		}
	}
	
	public function add($content)
	{
		$this->application->load("response")->addContent($content);
	}
	
	public function getTemplatePath()
	{
		return $this->getViewDir()."/".$this->getViewFileName().".".$this->renderer['extension'];
	}
	
	protected function getViewDir()
	{
		$doc_root	= $this->application->load("config")->get("doc_root");
		$proj_path	= $this->application->load("config")->get("project_dir");
		$tpl_path	= $this->application->load("config")->get("template_dir");
		$def_tpl	= $this->application->load("config")->get("DefaultTemplate");
		$view_path	= $this->application->load("config")->get("view_dir");
		
		//Prüfen, ob SubController oder MainController
		if(!$this->is_subcontroller)
		{
			$controller	= $this->application->load("FrontController")->getActiveController();
		}
		else
		{
			//SubController Name muss beim erzeugen des Views im Constructor übergeben werden.
			$controller = $this->application->load("FrontController")->getActiveSubController();
		}
		
		if($tpl_path && $def_tpl)
		{
			$path	= $doc_root."/".$proj_path."/".$tpl_path."/".$def_tpl."/".$view_path."/".$controller;
			
			return $path;
		}
		else
		{
			die(__METHOD__ . ": die Eigenschaften template_dir und DefaultTemplate muessen in der Config eingestellt werden.");
		}
		
		return false;
	}
	
	protected function getViewFileName()
	{
		if(!$this->is_subcontroller)
		{
			$filename	= $this->application
							->load("FrontController")->getActiveAction();
		}
		else
		{
			$filename	= $this->application
							->load("FrontController")->getActiveSubController();
		}
		
		return $filename;
	}

	protected function setRenderer(array $renderer)
	{
		$this->renderer	= $renderer;
	}
	
}

?>