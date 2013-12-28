<?php

namespace wsc\view;
use wsc\application\Application;
/**
 *
 * @author Michi
 *        
 */
abstract class view_abstract 
{
	protected $application;
	/**
	 * Muss von jeder abgeleitenen View Klasse implementiert werden.
	 * 
	 * Gibt die fertige View aus.
	 */
	
	public function __construct()
	{
		$this->application = Application::getInstance();
	}
	
	abstract public function render();
	
	final public function display()
	{
		$content	= $this->render();
		$response	= $this->application->load("Response");
		
		$response->addContent($content);
		$response->send();
	}
}

?>