<?php

namespace wsc\validator;

use wsc\validator\ValidatorInterface;
/**
 *
 * @author Michi
 *        
 */
abstract class ValidatorAbstract implements ValidatorInterface 
{	
	const MESSAGE_KEY	= "noMessageKey";
	const NO_MESSAGE	= "noMessage";
	
	protected $messageOptions	= array(
		"messages"			=> array(),
		"messageVars"		=> array(),
		"messageTemplates"	=> array()
	);
	
	protected $value	= NULL;
	
	public function __construct(array $options = NULL)
	{
		if(isset($this->message_templates))
		{
			$this->messageOptions['messageTemplates']	= $this->message_templates;
		}
		if(isset($this->message_vars))
		{
			$this->messageOptions['messageVars']	= $this->message_vars;
		}
	}
	
	/**
	 * Setzt den zu �berpr�fenden Inhalt.
	 * 
	 * @param mixed $value
	 */
	protected function setValue($value)
	{
		$this->value	= $value;
	}
	
	/**
	 * Gibt den zu �berpr�fenden Inhalt zur�ck.
	 * 
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}
	
	/**
	 * Gib die vorhanden Nachrichten von invaliden �berpr�fungen in einem Array zur�ck.
	 * 
	 * @see \wsc\validator\ValidatorInterface::getMessage()
	 * @return array
	 */
	public function getMessage()
	{
		return $this->messageOptions['messages'];
	}
	
	/**
	 * Legt eine Nachricht f�r den �bergebenen Message Key fest.
	 * Die Nachricht wird bei invaliden �berpr�fungen erzeugt.
	 * 
	 * @param string $message_key 	Mei�t eine Konstante
	 * @param string $message		Die Nachricht
	 */
	public function setMessage($message_key, $message)
	{
		$this->messageOptions['messageTemplates'][$message_key]	= $message;
		return $this;
	}
	
	/**
	 * Definiert eine Variable, f�r eine �berpr�fungsnachricht.
	 * 
	 * @param string $var				Name der Variable
	 * @param string|int|float $value	Inhalt der Variable
	 */
	protected function setMessageVar($var, $value)
	{
		$this->messageOptions['messageVars'][$var]	= $value;
	}
	
	/**
	 * Erzeugt eine �berpr�fungsnachricht und speichert diese. Es werden die
	 * Message Variablen durch die Werte ersetzt.
	 * 
	 * @param string $message_key	Meist eine Konstante
	 * @param string $message		(optional) Benutzerdefinierte Nachricht.
	 */
	protected function createMessage($message_key, $message = NULL)
	{
		$search		= array();
		$replace	= array();
		
		if(!isset($this->messageOptions['messageTemplates'][$message_key]))
		{
			if(!empty($message_key))
			{
				$this->messageOptions['messageTemplates'][$message_key]	= !empty($message) ? $message : self::NO_MESSAGE;
			}
			else
			{
				$this->createMessage(self::MESSAGE_KEY, self::NO_MESSAGE);
			}
		}
		else 
		{
			if(!is_null($message))
			{
				$this->setMessage($message_key, $message);
			}
			else
			{
				if(empty($this->messageOptions['messageTemplates'][$message_key]))
				{
					$this->setMessage($message_key, $message_key.", " . "(" . gettype($this->value) . ") " . $this->value );
				}
			}
		}
		
		if(!empty($this->messageOptions['messageVars']))
		{
			foreach ($this->messageOptions['messageVars'] as $var_name => $var_value)
			{
				$search[]	= "{" . $var_name . "}";
				$replace[]	= $var_value; 
			}
		}
		
		if(!empty($search) && !empty($this->messageOptions['messageTemplates']))
		{
			foreach ($this->messageOptions['messageTemplates'] as $key => $template)
			{
				$this->messageOptions['messageTemplates'][$key]	= str_replace($search, $replace, $template);
			}
		}
		
		$this->messageOptions['messages'][]	= $this->messageOptions['messageTemplates'][$message_key];
	}
	
	/**
	 * Wandelt die Kurzschreibwei�e in validen Code um.
	 * 
	 * @param mixed $value	Zu �berpr�fender Inhalt.
	 */
	protected function __invoke($value)
	{
		return $this->isValid($value);
	}
}

?>