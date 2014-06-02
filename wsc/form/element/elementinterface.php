<?php

namespace wsc\form\element;

/**
 *
 * @author Michi
 *        
 */
interface ElementInterface
{
    /**
     * Legt den Namen f�r das zu erzeugende Element fest.
     * 
     * @param string $element_name
     */
	public function __construct($element_name);
	
	/**
	 * Liefert alle Attribute eines Elements
	 */
	public function getAttributes();
	
	/**
	 * Gibt das angeforderte Attribut zur�ck.
	 * 
	 * @param string $attribute    Name des Attributes
	 * @return array
	 */
	public function getAttribute($attribute);
	
	/**
	 * Legt einen Attribute f�r ein Element fest.
	 * 
	 * @param string $attribute    Name des Attributes
	 * @param string $value        Inhalt des Attributes
	 * @return mixed
	 */
	public function setAttribute($attribute, $value);
	
	/**
	 * Setzt die �bermittelten Daten f�r das Element.
	 * 
	 * @param multitype:string|numeric $data
	 */
	public function setData($data);
	
	/**
	 * Gibt die �bermittelten Daten f�r das Element zur�ck.
	 * 
	 * @return multitype:string|int|float
	 */
	public function getData();
	
	/**
	 * Gibt zur�ck, ob das Element einen g�ltigen Inhalt hat.
	 * 
	 * @return boolean
	 */
	public function isValid();
	
	/**
	 * Gibt die Nachricht des Validators f�r das Formularfeld zur�ck.
	 * @return string
	 */
	public function getMessage();
	
	/**
	 * F�gt dem Element einen Validator hinzu.
	 * @param mixed $validator
	 */
	public function addValidator($validator);
	
	/**
	 * F�gt dem Element ein Label hinzu.
	 * 
	 * @param string $label
	 */
	public function setLabel($label);
	
	/**
	 * Gibt das Label des Elementes zur�ck.
	 * @return string
	 */
	public function getLabel();
	
	/**
	 * Gibt die Option "DB Tabelle" aus die dem Element zugewiesen ist.
	 */
	public function getDBTable();
	
	/**
	 * Gibt die Option "DB Tabellenfeld" aus die dem Element zugewiesen ist.
	 */
	public function getTableField();
	
	/**
	 * Weist dem Element ein Datenbanktabellenfeld zu.
	 * Der Parameter Tabelle ist dabei optional, wenn im Form Objekt eine Standardtabelle gew�hlt wurde.
	 * 
	 * @param string $field
	 * @param string $table
	 */
	public function setTableField($field, $table = null);
}

?>