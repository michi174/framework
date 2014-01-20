<?php
namespace wsc\controller;
use wsc\application\Application;
use wsc\view\View_abstract;
/** 
 * @author Michi
 * 
 */
abstract class controller_abstract extends BaseController
{	
	protected $application;
	protected $request;
	
	/**
	 * Überprüft, ob ein gültiger Controller aufgerufen wird.
	 * 
	 * @param resource $controller	Das zu prüfende Controller Objekt.
	 * @return boolean
	 */
	public static function isValidController($controller)
	{
		if($controller instanceof controller_abstract)
		{
			return true;
		}
		else
		{
			//DEBUG:echo ("Der Controller &rsquo;" . $controller . "&rsquo; ist nicht gueltig.");
			return false;
		}
	}
	
	public function __construct()
	{
		$this->application	= Application::getInstance();
		$this->request		= $this->application->load("request");
	}
	
	abstract public function default_action();
}

?>