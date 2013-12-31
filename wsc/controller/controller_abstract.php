<?php
namespace wsc\controller;
use wsc\application\Application;
/** 
 * @author Michi
 * 
 */
abstract class controller_abstract 
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
	
	public function __construct(Application $application)
	{
		$this->application	= $application;
		$this->request		= $this->application->load("request");
	}
	
	abstract public function default_action();
}

?>