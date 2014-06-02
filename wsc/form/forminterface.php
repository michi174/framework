<?php
namespace wsc\form;
use wsc\form\element\ElementInterface;
use wsc\database\Database;

interface FormInterface
{
	/**
	 * F�gt der Form ein Element hinzu.
	 * 
	 * @param ElementInterface $element
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
    
    /**
     * F�hrt die Datenbankoperation durch.
     */
    public function executeDatabase();
    
    /**
     * Datenbankfunktionen aktivieren.
     * Erfordert eine Datenbankverbindung als Paramenter.
     * 
     * @param Database $database
     */
    public function enableDBFunctions($db);
    
    /**
     * W�hlt den/die Datens�tze aus, die bearbeitet, oder gel�scht werden soll.
     * 
     * @param string $db_field  Das Feld in der Tabelle
     * @param string $value     Der Wert mit dem das Feld bef�llt ist.
     */
    public function setUpdateID($db_field, $value);
    
    /**
     * W�hlt eine Standardtabelle f�r die Datenbankoperation aus, damit
     * nicht jedes Form Element Element die Tabelle mitgeben muss.
     * 
     * Ist im Form Element allerdings eine Tabelle mitgegeben, wird die Tabelle
     * des Elementes verwendet und nicht diese Standardtabelle.
     * 
     * @param string $table
     */
    public function setDefaultTable($table);
    
    /**
     * Befehl der an die Datenbank gesendet wird. (Update, Insert...)
     * Es muss eine Konstante dieser Klasse �bergeben werden.
     * 
     * @param string $db_mod
     */
    public function setDBMod($db_mod);
    
    /**
     * Gibt die M�glichkeit, dass Datenbankfelder bef�llt werden, ohne dass die Daten aus einem Form Element
     * �bergeben werden m�ssen. Beispielsweise das Erstellungsdatum des Datensatzes kann so ohne Input oder Hidden Field
     * in die Datenbank geschrieben werden.
     * 
     * @param string $table     Tabelle, in die der Wert geschrieben wird. (Optional, wenn nicht �bergeben, wird die Standardtabelle des Form Objektes verwendet)
     * @param string $field     Feld in der Tabelle
     * @param string $value     Wert mit dem das Tabellenfeld bef�llt werden soll.
     */
    public function addManualDBField($table = null, $field, $value);
}
?>