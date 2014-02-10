<?php

namespace wsc\form\element;

/**
 *
 * @author Michi
 *        
 */
interface ElementInterface
{
	public function __construct($element_name);
	
	/**
	 * Liefert alle Attribute eines Elements
	 */
	public function getAttributes();
	
	/**
	 * Gibt das angeforderte Attribut zurück.
	 * 
	 * @param string $attribute    Name des Attributes
	 */
	public function getAttribute($attribute);
	
	
	public function setAttribute($attribute, $value);
}

?>