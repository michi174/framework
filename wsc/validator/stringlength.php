<?php

namespace wsc\validator;

use wsc\validator\ValidatorAbstract;

/**
 *
 * @author Michi
 *        
 */
class StringLength extends ValidatorAbstract 
{
	const IS_INVALID	= "invalidDataType";
	const IS_TOO_LONG	= "stringTooLong";
	const IS_TOO_SHORT	= "stringTooShort";
	
	protected $options	= array(
		"min"	=> 0,
		"max"	=> PHP_INT_MAX
	);
	
	protected $message_templates	= array(
		self::IS_INVALID	=> "Ungueltiger Datentyp",
		self::IS_TOO_LONG	=> "Darf maximal {max} Zeichen lang sein.",
		self::IS_TOO_SHORT	=> "Muss mindestens {min} Zeichen lang sein."
	);
	
	public function __construct(array $options = array())
	{
		parent::__construct();
		
		if(!empty($options))
		{
			foreach ($options as $option => $value)
			{
				$option	= strtolower($option);
				
				if(array_key_exists($option, $this->options))
				{
					$this->{"set".ucfirst($option)}($value);
				}
				else
				{
					throw new \Exception("Option kann nicht verwendet werden.");
				}
			}
		}
		$this->setMessageVar("min", $this->getMin());
		$this->setMessageVar("max", $this->getMax());
	}
	
	public function setMin($min)
	{
		if(is_int($min))
		{
			$this->options['min']	= $min;
			$this->setMessageVar("min", $min);
		}
		else
		{
			throw new \Exception("Inhalt der Option muss vom Typ Integer sein.");
		}
	}
	
	public function setMax($max)
	{
		if(is_int($max))
		{
			$this->options['max']	= $max;
			$this->setMessageVar("max", $max);
		}
		else
		{
			throw new \Exception("Inhalt der Option muss vom Typ Integer sein.");
		}
	}
	
	public function getMin()
	{
		return $this->options['min'];
	}
	
	public function getMax()
	{
		return $this->options['max'];	
	}
	
	/**
	 * 
	 * @see \wsc\validator\ValidatorInterface::isValid()
	 *
	 */
	public function isValid($value)
	{
		$this->setValue($value);
		
		if(is_object($value) || is_array($value))
		{
			$this->createMessage(self::IS_INVALID);
			return false;
		}
		
		(string)$value;
		
		$length	= strlen($value);
		
		if($length > $this->getMax())
		{
			$this->createMessage(self::IS_TOO_LONG);
			return false;
		}
		
		if($length < $this->getMin())
		{
			$this->createMessage(self::IS_TOO_SHORT);
			return false;
		}
		
		return true;
	}
}

?>