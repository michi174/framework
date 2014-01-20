<?php

namespace wsc\validator;

use wsc\validator\ValidatorAbstract;

/**
 *
 * @author Michi
 *        
 */
class NotEmpty extends ValidatorAbstract
{
	const IS_EMPTY		= 'EmptyValue';
	const IS_INVALID	= 'InvalidDataType';
	
	const BOOL		= "bool";
	const INT		= "int";
	const FLOAT 	= "float";
	const STRING	= "string";
	const E_ARRAY	= "array";
	const OBJECT	= "object";
	const ZERO		= "zero";
	
	/**
	 * Standardfehlermeldungen
	 * @var array
	 */
	protected $message_templates	= array(
		self::IS_EMPTY		=> "leerer Parameter",
		self::IS_INVALID	=> "ungueltiger Datentyp"
	);
	
	/**
	 * Beinhaltet die Strings aller erlaubten Datentypen.
	 * 
	 * @var array 
	 */
	protected $allowedTypes	= array(
		self::BOOL,		// True, False
		self::INT,		// 0,1,2,3,...
		self::FLOAT,	// 0.1, 1.4,...
		self::STRING,	// Text
		self::E_ARRAY,	// Array
		self::OBJECT,	// Objekt
		self::ZERO		// 0, 0.0
	);
	
	/**
	 * Beihaltet alle vom Konstruktor übergebenen Optionen.
	 * 
	 * @var array
	 */
	protected $options	= array();
	
	/**
	 * Verarbeitet die vom Benutzer übergebenen Optionen.
	 * 
	 * @param array | constant $options		Optionen (zB. IsEmpty::INT) - gib nur true zurück wenn der Datentyp des Parameters INT ist.
	 */
	public function __construct($options = NULL)
	{
		parent::__construct();
		
		if(!is_array($options) && !is_null($options))
		{
			$options	= func_get_args();
			
			if(!empty($options))
			{
				$options['types'][]	= $options;
			}
		}
		
		if(is_array($options))
		{
			foreach ($options as $option)
			{
				$this->options['types'][] = $option;
			}
		}
		
	}
	
	/**
	 * Bestimmt den Datentyp des übergebenen Inhaltes.
	 * 
	 * @param mixed $value
	 * @return string Type
	 */
	private function getType($value)
	{
		$type	= null;
		
		if (!is_null($value) && !is_bool($value) && !is_array($value) && !is_float($value) && !is_int($value) && 
			!is_string($value) && !is_object($value))
		{
			$this->createMessage(self::IS_INVALID);
		}
		
		if(is_bool($value))
		{
			$type	= self::BOOL;
		}
		elseif(is_int($value))
		{
			$type	= self::INT;
		}
		elseif(is_float($value))
		{
			$type	= self::FLOAT;
		}
		elseif(is_string($value))
		{
			$type	= self::STRING;
		}
		elseif(is_array($value))
		{
			$type	= self::E_ARRAY;
		}
		elseif(is_object($value))
		{
			$type	= self::OBJECT;
		}
		else 
		{
			echo (self::IS_INVALID);
		}
		
		return $type;
	}
	
	/**
	 * Überprüft, ob der Inhalt 0 ist (int, float).
	 * @return boolean
	 */
	private function isZero()
	{
		if($this->value === 0 || $this->value === 0.0)
		{
			return true;
		}
		
		return false;		
	}
	
	/**
	 * Prüft, ob der übergebene Parameter leer ist, oder nicht.
	 * Wird ein Objekt übergeben, ist dieses immer true.
	 *
	 * @see \wsc\validator\ValidatorInterface::isValid()
	 *
	 */
	public function isValid($value) 
	{
		$this->setValue($value);
		$type	= $this->getType($value);
		
		//Dateityp nicht in den Optionen vorhanden.
		if(isset($this->options['types']) && !in_array($type, $this->options['types']))
		{
			$this->createMessage(self::IS_INVALID);
			return false;
		}
		
		//Ungültiger Typ
		if(!in_array($type, $this->allowedTypes))
		{
			$this->createMessage(self::IS_INVALID);
			return false;
		}
		
		//0 ist nicht erlaubt.
		if(!in_array(self::ZERO, $this->allowedTypes) && $this->isZero() === true)
		{
			$this->createMessage(self::IS_EMPTY);
			return false;
		}
		
		
		switch($type)
		{
			//String
			case self::STRING:
				if($value === '')
				{
					$this->createMessage(self::IS_EMPTY);
					return false;
				}
					
				break;
			//Int (0)
			case self::INT:
				if($value === 0)
				{
					$this->createMessage(self::IS_EMPTY);
					return false;
				}
				break;
			//Float (0.0)
			case self::FLOAT:
				if($value === 0.0)
				{
					$this->createMessage(self::IS_EMPTY);
					return false;
				}
				break;
			//Array()
			case self::E_ARRAY:
				if($value === array())
				{
					$this->createMessage(self::IS_EMPTY);
					return false;
				}
				break;
			case self::BOOL:
				if($value === false)
				{
					$this->createMessage(self::IS_EMPTY);
					return false;
				}
				break;
		}
		return true;
	}
}
?>