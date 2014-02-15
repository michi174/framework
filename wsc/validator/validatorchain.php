<?php

namespace wsc\validator;

/**
 *
 * @author Michi
 *        
 */
class ValidatorChain extends ValidatorAbstract 
{
	const NO_VALIDATOR	= 'noValidators';
	
	protected $validators			= array();
	protected $message_templates	= array(
		self::NO_VALIDATOR	=> "Es sind keine Validatoren zum validieren des Wertes vorhanden."
	);
	
	public function __construct($validators = NULL)
	{
		if(!is_null($validators))
		{
			$this->add($validators);
		}
	}
	
	/**
	 * Fügt der Kette einen weiteren Validator hinzu.
	 * 
	 * @param ValidatorInterface $validator
	 * @throws \Exception
	 */
	public function add($validators)
	{
		if(!is_array($validators))
		{
			
			$temp	= array();
			array_push($temp, $validators);
			
			$validators	= $temp;
		}
		
		foreach ($validators as $validator)
		{
			if($validator instanceof  ValidatorInterface)
			{
				array_push($this->validators, $validator);
			}
			else
			{
				throw new \Exception("Ungueltiger Validator");
			}
		}
	}
	
	/**
	 * Prüft den Wert mit allen vorhandenen Validatoren.
	 * 
	 * @see \wsc\validator\ValidatorInterface::isValid()
	 * @param mixed $value Der zu prüfende Wert
	 */
	public function isValid($value)
	{
		$this->setValue($value);
		
		if(!empty($this->validators))
		{
			foreach ($this->validators as $validator)
			{
				if(!$validator->isValid($value))
				{
					foreach ($validator->getMessage() as $message)
					{
						$this->createMessage(get_class($validator), $message);
					}
					
					return false;
				}
			}
			return true;
		}
		else 
		{
			$this->createMessage(self::NO_VALIDATOR);
			return true;
		}
	}
}

?>