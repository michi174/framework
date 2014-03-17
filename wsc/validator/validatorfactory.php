<?php

namespace wsc\validator;

/**
 *
 * @author Michi
 *        
 */
class ValidatorFactory 
{
	/**
	 * Erzeugt ein Objekt des angeforderten Validators und gibt dieses zurück.
	 * 
	 * @param string $validator    Angeforderter Validator
	 * @throws \Exception
	 * @return ValidatorInterface
	 */
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
	
	/**
	 * Überprüft, ob der übergebene Validator gültig ist.
	 * 
	 * @param string $validator    Zu prüfender Validator
	 * @return boolean
	 */
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