<?php

namespace wsc\view;

use wsc\view\View_abstract;
use wsc\template\Template;

/**
 *
 * @author Michi
 *        
 */
class View_template extends View_abstract 
{
	
	private $is_subcontroller	= false;
	
	private $template;
	
	
	public function __construct($subcontroller = false)
	{
		parent::__construct();
		
		if($subcontroller !== false)
		{
			$this->is_subcontroller = $subcontroller;
		}
		
		$this->template		= new Template;
		$this->setTemplate();
	}
	
	public function assignVar($tpl_vars, $replace = NULL)
	{
		$this->template->assign($tpl_vars, $replace);
	}
	public function assignDatarow($name, $sql, $vars)
	{
		$this->template->assignDatarow($name, $sql, $vars);
	}
	public function assignSubrow($name, $parent, $sql, $vars)
	{
		$this->template->assignSubrow($name, $parent, $sql, $vars);
	}
	public function assignFunction($var, $function)
	{
		$this->template->assignFunction($var, $function);
	}

	
	private function setTemplateDir()
	{
		$doc_root	= $this->application->load("config")->get("doc_root");
		$proj_path	= $this->application->load("config")->get("project_dir");
		$tpl_path	= $this->application->load("config")->get("template_dir");
		$def_tpl	= $this->application->load("config")->get("DefaultTemplate");
		$view_path	= $this->application->load("config")->get("view_dir");
		
		if(!$this->is_subcontroller)
		{
			$controller	= $this->application->load("FrontController")->getActiveController();
		}
		else
		{
			$controller = $this->is_subcontroller;
		}
		
		if($tpl_path && $def_tpl)
		{
			$path	= $doc_root."/".$proj_path."/".$tpl_path."/".$def_tpl."/".$view_path."/".$controller;
		}
		else
		{
			die(__METHOD__ . ": die Eigenschaften template_dir und DefaultTemplate muessen in der Config eingestellt werden.");
		}
		
		$this->template->setTemplateDir($path);
		
	}
	private function setTemplateName()
	{
		if(!$this->is_subcontroller)
		{
			$action	= $this->application->load("FrontController")->getActiveAction();
		}
		else
		{
			$action	= $this->is_subcontroller;
		}
		
		$this->template->addTemplate($action.".html");
	}
	
	private function setTemplate()
	{
		$this->setTemplateDir();
		$this->setTemplateName();
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \wsc\view\view_abstract::render()
	 *
	 */
	protected function render() 
	{
		return $this->template->display($render = true);
	}
}

?>