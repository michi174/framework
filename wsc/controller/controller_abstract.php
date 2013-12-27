<?php
namespace wsc\controller;

use wsc\http\Request\Request;
use wsc\application\Application;
/** 
 * @author Michi
 * 
 */
abstract class controller_abstract 
{	
	protected $application;
	protected $request;
	
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