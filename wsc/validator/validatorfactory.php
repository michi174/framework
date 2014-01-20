<?php

namespace wsc\validator;

/**
 *
 * @author Michi
 *        
 */
class ValidatorFactory 
{
	
	public function getValidator($validator)
	{
		if($this->isValidator($validator))
		{
			$class = __NAMESPACE__."\\".$validator;
			return new $class;
		}
		else
		{
			throw new \Exception("ValidatorFactory kann aus dem Validator ". $validator ." kein Objekt erzeugen.");
		}
	}
	
	private function isValidator($validator)
	{
		$class	= __NAMESPACE__."\\".$validator;
		
		if(class_exists($class))
		{
			return true;
		}
		else
			return false;
	}
}

?>