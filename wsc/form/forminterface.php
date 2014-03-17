<?php
namespace wsc\form;
use wsc\form\element\ElementInterface;

interface FormInterface
{
	/**
	 * Fügt der Form ein Element hinzu.
	 * 
	 * @param Element $element
	 */
    public function add(ElementInterface $element);
    
    /**
     * Gibt ein Element der Form zurück.
     *
     * @param string $name     Name des Elements
     * @return ElementInterface
     */
    public function get($element);
    
    /**
     * Fügt einen Attribut hinzu.
     *
     * @param string $attribute    Attributname
     * @param string $value        Inhalt des Attributes
     */
    public function setAttribute($attribute, $value);
    
    /**
     * Gibt den Wert des gesuchten Attributes zurück.
     * 
     * @param string $attribute     Name des gesuchten Attributes
     * @return string
     */
    public function getAttribute($attribute);
    
    /**
     * Gibt alle Attribute einer Form zurück
     * @return array
     */
    public function getAttributes();
    
    /**
     * Fügt der gesamten Form einen oder mehrere Validatoren hinzu.
     *
     * @param multitype:string|array|ValidatorInterface $validators
     */
    public function addValidators($validator);
    
    /**
     * Legt den zu überprüfenden Inhalt der Form fest.
     * Meißt ein ($_POST Array).
     *
     * @param array $data
     */
    public function setData(array $data);
    
    /**
     * Gibt die übertragenen Daten für das Element zurück.
     * @param string $element       Name des Elementes
     */
    public function getData($element);
    
    /**
     * Gibt zurück ob die Form valide ist oder nicht.
     *
     * @return boolean
     */
    public function isValid();
    
    /**
     * Gibt die Nachrichten eines oder mehrerer Elemente zurück.
     * Ist der Optionale Parameter '$element' leer, wird ein mehrdimensionales
     * Array aller Elementnamen, samt aller Nachrichten für das Element zurückgegeben.
     *
     * @param string $element   Element für das die Nachricht abgerufen wird.
     * @return array
     */
    public function getMessages($element = null);
}

?>