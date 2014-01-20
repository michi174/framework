<?php

namespace wsc\validator;

/**
 *
 * @author Michi
 *        
 */
class Between extends ValidatorAbstract {
	
	const IS_INVALID	= "InvalidDataType";
	const IS_TOO_BIG	= "IsTooBig";
	const IS_TOO_SMALL	= "IsTooSmall";
	const IS_NOT_STRICT	= "IsNotStrictBetween";
	
	const STRICT	= "strict";
	
	protected $message_templates	= array(
		self::IS_INVALID	=> "ungueltiger Datentyp",
		self::IS_TOO_BIG	=> "muss < {max} sein",
		self::IS_TOO_SMALL	=> "muss > {min} sein",
		self::IS_NOT_STRICT	=> "muss genau (strict) zwischen {min} und {max} sein",
	);
	protected $options				= array(
		'min'	=> 0,
		'max'	=> PHP_INT_MAX
	);
	
	public function __construct($options = NULL)
	{
		parent::__construct();
		
		if(!is_null($options))
		{
			if(!is_array($options))
			{
				array_push($this->options, $options);
			}
			if(is_array($options))
			{
				foreach ($options as $option => $value)
				{
					if(array_key_exists($option, $this->options))
					{
						$this->options[$option]	= $value;
					}
					else
					{
						$this->options[$value] = $value;
					}
				}
			}
		}
		$this->setMessageVar("min", $this->getMin());
		$this->setMessageVar("max", $this->getMax());
	}
	
	public function setMin($min)
	{
		if(is_numeric($min))
		{
			$this->options['min']	= $min;
			$this->setMessageVar("min", $min);
		}
	}
	
	public function setMax($max)
	{
		if(is_numeric($max))
		{
			$this->options['max']	= $max;
			$this->setMessageVar("max", $max);
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
		
		if(!is_numeric($value))
		{
			$this->createMessage(self::IS_INVALID);
			return false;
		}
		
		if($value > $this->getMax())
		{
			$this->createMessage(self::IS_TOO_BIG);
			return false;
		}
		if($value < $this->getMin())
		{
			$this->createMessage(self::IS_TOO_SMALL);
			return false;
		}
		
		if(isset($this->options['strict']))
		{
			if($value >= $this->getMax())
			{
				$this->createMessage(self::IS_TOO_BIG);
				$this->createMessage(self::IS_NOT_STRICT);
				return false;
			}
			
			if($value <= $this->getMin())
			{
				$this->createMessage(self::IS_TOO_SMALL);
				$this->createMessage(self::IS_NOT_STRICT);
				return false;
			}
		}
		return true;
	}
}

?>