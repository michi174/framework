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
	 * Überprüft, ob ein SubController gültig ist.
	 * 
	 * Ein SubController ist gültig, wenn er von dieser Klasse erbt und entweder
	 * die Methode runBeforMain oder runAfterMain enthält.
	 * 
	 * Es können auf beide Methoden vorhanden sein.
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