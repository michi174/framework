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
	 * �berpr�ft, ob ein g�ltiger Controller aufgerufen wird.
	 * 
	 * @param resource $controller	Das zu pr�fende Controller Objekt.
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