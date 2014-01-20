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
	
	
	public function __construct($is_subcontroller = false)
	{
		parent::__construct($is_subcontroller);
		
		$this->setRenderer(array(
			'name'		=> 'TEMPLATE',
			'extension'	=> 'html'
		));
		
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
		$this->template->setTemplateDir($this->getViewDir());
	}
	private function setTemplateName()
	{
		$this->template->addTemplate($this->getViewFileName().".html");
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
	public function render($content) 
	{
		$this->template->setContentToRender($content);
		return $this->template->display($render = true);
	}
}

?>