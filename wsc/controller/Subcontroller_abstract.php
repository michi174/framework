<?php
namespace wsc\controller;
/**
 *
 * @author Michi
 *        
 */
abstract class Subcontroller_abstract extends BaseController
{
	//protected $view;
	
	/**
	 * �berpr�ft, ob ein SubController g�ltig ist.
	 * 
	 * Ein SubController ist g�ltig, wenn er von dieser Klasse erbt und entweder
	 * die Methode runBeforMain oder runAfterMain enth�lt.
	 * 
	 * Es k�nnen auf beide Methoden vorhanden sein.
	 * 
	 * @param resource $subController	Objekt des Subcontrollers
	 * @return boolean 
	 */
	public static function isValidSubController($subController)
	{
		if($subController instanceof Subcontroller_abstract)
		{
			if(is_callable(array($subController, "runBeforeMain")) || is_callable(array($subController, "runAfterMain")))
			{
				return true;
			}
			else 
			{
				echo "SubController &rsquo;".get_class($subController)."&rsquo; enthaelt keine Methode &rsquo;runBeforMain&rsquo; oder &rsquo;runAfterMain&rsquo;<br />";
			}
		}
		else
		{
			echo "SubController &rsquo;".get_class($subController)."&rsquo; erbt nicht von &rsquo;controller_abstract&rsquo; und ist daher kein gueltiger SubController.<br />";
		}
		return false;
	}
	
	protected function getSubControllerName($object)
	{
		$class			= get_class($object);
		$subcontroller	= explode("\\", $class);
		
		return end($subcontroller);
	}
}

?>