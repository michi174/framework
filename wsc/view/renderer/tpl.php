<?php

namespace wsc\view\renderer;

use wsc\template\Template;
/**
 *
 * @author Michi
 *        
 */

class Tpl extends AbstractRenderer 
{
	private $template	= null;
	
	public function __construct()
	{
		$this->template	= new Template();
		$this->setOption("fileextension", "html");
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
	
	/**
	 * (non-PHPdoc)
	 *
	 *
	 */
	public function render($content)
	{
		$this->template->setContentToRender($content);
		return $this->template->display($render = true);
	}
}

?>