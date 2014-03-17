<?php
namespace wsc\form;
use wsc\form\element\ElementInterface;

interface FormInterface
{
	/**
	 * F�gt der Form ein Element hinzu.
	 * 
	 * @param Element $element
	 */
    public function add(ElementInterface $element);
    
    /**
     * Gibt ein Element der Form zur�ck.
     *
     * @param string $name     Name des Elements
     * @return ElementInterface
     */
    public function get($element);
    
    /**
     * F�gt einen Attribut hinzu.
     *
     * @param string $attribute    Attributname
     * @param string $value        Inhalt des Attributes
     */
    public function setAttribute($attribute, $value);
    
    /**
     * Gibt den Wert des gesuchten Attributes zur�ck.
     * 
     * @param string $attribute     Name des gesuchten Attributes
     * @return string
     */
    public function getAttribute($attribute);
    
    /**
     * Gibt alle Attribute einer Form zur�ck
     * @return array
     */
    public function getAttributes();
    
    /**
     * F�gt der gesamten Form einen oder mehrere Validatoren hinzu.
     *
     * @param multitype:string|array|ValidatorInterface $validators
     */
    public function addValidators($validator);
    
    /**
     * Legt den zu �berpr�fenden Inhalt der Form fest.
     * Mei�t ein ($_POST Array).
     *
     * @param array $data
     */
    public function setData(array $data);
    
    /**
     * Gibt die �bertragenen Daten f�r das Element zur�ck.
     * @param string $element       Name des Elementes
     */
    public function getData($element);
    
    /**
     * Gibt zur�ck ob die Form valide ist oder nicht.
     *
     * @return boolean
     */
    public function isValid();
    
    /**
     * Gibt die Nachrichten eines oder mehrerer Elemente zur�ck.
     * Ist der Optionale Parameter '$element' leer, wird ein mehrdimensionales
     * Array aller Elementnamen, samt aller Nachrichten f�r das Element zur�ckgegeben.
     *
     * @param string $element   Element f�r das die Nachricht abgerufen wird.
     * @return array
     */
    public function getMessages($element = null);
}

?>