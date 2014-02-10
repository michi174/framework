<?php

namespace wsc\form\element;

use wsc\validator\ValidatorChain;
use wsc\validator\NotEmpty;
/**
 *
 * @author Michi
 *        
 */
class Element implements ElementInterface
{
	const INPUT		= "input";
	const SELECT	= "select";
	const OPTION	= "option";
	
	const DEFAULT_ELEMENT_TYPE	= self::INPUT;
	
	
	private $attributes	= array();
	
	private $options	= array(
		'element'	=> self::DEFAULT_ELEMENT_TYPE
	);
	
	private $validators	= array();
	
	private $tags	= array(
			
		self::INPUT		=> array(
			'start'		=> "<input>",
			'end'		=> ""
		),
		self::SELECT	=> array(
			'start'		=> "<select>",
			'end'		=> "</select>"
		),
		self::OPTION	=> array(
			'start'		=> "<option>",
			'end'		=> "</option>"
		)
	);
	
	
	public function __construct($element_name)
	{
		$this->setAttribute("name", $element_name);
	}
	
	/**
	 * F�gt dem Element einen Attribut hinzu.
	 * 
	 * @param string $attribute		Der Attribut
	 * @param string $value			Inhalt des Attributes
	 */
	public function setAttribute($attribute, $value = "")
	{
		$this->attributes[$attribute]	= $value;
	}
	
	/**
	 * Legt ein Label f�r das Element fest.
	 * 
	 * @param string $label		Label
	 */
	public function setLabel($label)
	{
		$this->options['label']	= $label;
	}
	
	/**
	 * Gibt das Label zur�ck. Wird der HTML Parameter auf true gesetzt, wird ein Label Tag erzeugt.
	 * 
	 * @param boolean $html		Bestimmt ob als reiner Text oder als <label> HTML-Tag
	 * @return string
	 */
	public function getLabel($html = false)
	{
		if($html)
		{
			if(!isset($this->attributes['id']))
			{
				$this->setAttribute("id", $this->attributes['name']);
			}
			
			if(isset($this->options['label']))
			{
				return "<label for=\"".$this->attributes['id']. "\">".$this->options['label']."</label>";
			}
		}
		
		return $this->options['label'];

	}
	
	/**
	 * Gibt den Inhalt des Feldes zur�ck.
	 * 
	 * @return mixed $value		Der Inhalt des Elements
	 */
	public function getValue()
	{
		if(isset($this->attributes['value']))
		{
			return $this->attributes['value'];
		}
		
		else 
		{
			return null;
		}
	}
	
	/**
	 * Pr�ft, ob das Element g�ltig ist.
	 * 
	 * @return boolean
	 */
	public function isValid()
	{
		if(isset($this->attributes['value']))
		{
			$chain		= new ValidatorChain();
			$required	= new NotEmpty();
			
			foreach ($this->validators as $validator)
			{
				$chain->add($validator);
			}
			
			if(!$chain->isValid($this->attributes['value']))
			{
				return false;
			}
			
		}
		
		if(isset($this->attributes['required']) && !isset($this->attributes['value']) || !$required->isValid($this->attributes['value']))
		{
			return false;
		}
		
		return true;
	}
	
	/**
	 * F�gt dem Element einen Validator hinzu.
	 * 
	 * @param multitype: ValidatorInterface | array | string $validator
	 */
	public function addValidator($validator)
	{
		
	}
	
	/**
	 * Macht das Element zu einem Pflichtfeld.
	 */
	public function setRequired()
	{
		$this->setAttribute("required", "required");
	}

	public function getAttributes()
	{
	    return $this->attributes;
	}
	
	public function getAttribute($attribute)
	{
	    return isset($this->attributes[$attribute]) ? $this->attributes[$attribute] : null;
	}
}

?>