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
     * Legt den Namen für das zu erzeugende Element fest.
     * 
     * @param string $element_name
     */
	public function __construct($element_name);
	
	/**
	 * Liefert alle Attribute eines Elements
	 */
	public function getAttributes();
	
	/**
	 * Gibt das angeforderte Attribut zurück.
	 * 
	 * @param string $attribute    Name des Attributes
	 * @return array
	 */
	public function getAttribute($attribute);
	
	/**
	 * Legt einen Attribute für ein Element fest.
	 * 
	 * @param string $attribute    Name des Attributes
	 * @param string $value        Inhalt des Attributes
	 * @return mixed
	 */
	public function setAttribute($attribute, $value);
	
	/**
	 * Setzt die übermittelten Daten für das Element.
	 * 
	 * @param multitype:string|numeric $data
	 */
	public function setData($data);
	
	/**
	 * Gibt die übermittelten Daten für das Element zurück.
	 * 
	 * @return multitype:string|int|float
	 */
	public function getData();
	
	/**
	 * Gibt zurück, ob das Element einen gültigen Inhalt hat.
	 * 
	 * @return boolean
	 */
	public function isValid();
	
	/**
	 * Gibt die Nachricht des Validators für das Formularfeld zurück.
	 * @return string
	 */
	public function getMessage();
	
	/**
	 * Fügt dem Element einen Validator hinzu.
	 * @param mixed $validator
	 */
	public function addValidator($validator);
	
	/**
	 * Fügt dem Element ein Label hinzu.
	 * 
	 * @param string $label
	 */
	public function setLabel($label);
	
	/**
	 * Gibt das Label des Elementes zurück.
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
	 * Der Parameter Tabelle ist dabei optional, wenn im Form Objekt eine Standardtabelle gewählt wurde.
	 * 
	 * @param string $field
	 * @param string $table
	 */
	public function setTableField($field, $table = null);
}

?>