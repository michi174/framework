<?php
namespace wsc\form;
use wsc\form\element\ElementInterface;
use wsc\database\Database;

interface FormInterface
{
	/**
	 * Fügt der Form ein Element hinzu.
	 * 
	 * @param ElementInterface $element
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
    
    /**
     * Führt die Datenbankoperation durch.
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
     * Wählt den/die Datensätze aus, die bearbeitet, oder gelöscht werden soll.
     * 
     * @param string $db_field  Das Feld in der Tabelle
     * @param string $value     Der Wert mit dem das Feld befüllt ist.
     */
    public function setUpdateID($db_field, $value);
    
    /**
     * Wählt eine Standardtabelle für die Datenbankoperation aus, damit
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
     * Es muss eine Konstante dieser Klasse übergeben werden.
     * 
     * @param string $db_mod
     */
    public function setDBMod($db_mod);
    
    /**
     * Gibt die Möglichkeit, dass Datenbankfelder befüllt werden, ohne dass die Daten aus einem Form Element
     * übergeben werden müssen. Beispielsweise das Erstellungsdatum des Datensatzes kann so ohne Input oder Hidden Field
     * in die Datenbank geschrieben werden.
     * 
     * @param string $table     Tabelle, in die der Wert geschrieben wird. (Optional, wenn nicht übergeben, wird die Standardtabelle des Form Objektes verwendet)
     * @param string $field     Feld in der Tabelle
     * @param string $value     Wert mit dem das Tabellenfeld befüllt werden soll.
     */
    public function addManualDBField($table = null, $field, $value);
}
?>