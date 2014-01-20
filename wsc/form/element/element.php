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
	
	private $decorators	= array();
	
	
	public function __construct($element_name)
	{
		$this->setAttribute("name", $element_name);
	}
	
	/**
	 * Fügt dem Element einen Attribut hinzu.
	 * 
	 * @param string $attribute		Der Attribut
	 * @param string $value			Inhalt des Attributes
	 */
	public function setAttribute($attribute, $value = "")
	{
		$this->attributes[$attribute]	= $value;
	}
	
	/**
	 * Legt ein Label für das Element fest.
	 * 
	 * @param string $label		Label
	 */
	public function setLabel($label)
	{
		$this->options['label']	= $label;
	}
	
	/**
	 * Legt fest, welches HTML-Element erstellt wird.
	 * (input, select, option, usw...)
	 * 
	 * @param string $element
	 */
	public function setElement($element)
	{
		$this->options['element']	= $element;
	}
	
	/**
	 * Gibt das den HTML Code des Elements zurück.
	 * 
	 * @return string
	 */
	public function getElement()
	{
		return $this->build();
	}
	
	/**
	 * Gibt das Label zurück. Wird der HTML Parameter auf true gesetzt, wird ein Label Tag erzeugt.
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
	 * Baut das HTML-Element aus den bestimmten Eigenschaften zusammen.
	 * unt gibt dieses zurück.
	 * 
	 * @return string
	 */
	private function build()
	{
		$attributes	= "";
		
		foreach ($this->attributes as $attribute => $value)
		{
			$attributes	.= "$attribute=\"$value\" ";
		}
		
		$start_tag	= substr($this->tags[$this->options['element']]['start'], 0, -1);
		$end_tag	= $this->tags[$this->options['element']]['end'];
		
		return $start_tag." ".$attributes.">".$end_tag;
	}
	
	/**
	 * Prüft, ob das Element gültig ist.
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
	 * Fügt dem Element einen Validator hinzu.
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
	
	/**
	 * Wenn versucht wird, das Objekt auszugeben, wird stattdessen,
	 * das Element zurück- bzw. ausgegeben.
	 */
	public function __toString()
	{
		return $this->getElement();
	}
}

?>