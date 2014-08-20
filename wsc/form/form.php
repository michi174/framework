<?php

namespace wsc\form;

use wsc\validator\ValidatorInterface;
use wsc\validator\ValidatorFactory;
use wsc\form\element\ElementInterface;
use wsc\application\Application;

/**
 * Die Form Klasse bietet die Funktionen um HTML Formen zu erzeugen, deren Eingabe zu Filtern
 * und den Inhalt zu Validieren.
 * 
 * @author Michi
 *        
 */
class Form implements FormInterface
{
    const DB_INSERT = "insert";
    const DB_UPDATE = "update";
    const DB_DELETE = "delete";
    const DB_SELECT = "select";
    
    private $database   = null;
    /**
     * Enthält die Attribute der Form
     * 
     * @var array
     */
	private $attributes    = array(
		'action'	=> NULL,
		'method'	=> "post"
	);
	
	/**
	 * Enthält alle Elemente der Form
	 * @var array
	 */
	private $elements      = array();
	
	/**
	 * Enthält alle Validatoren der Form.
	 * 
	 * @var array
	 */
	private $validators    = array();
	
	/**
	 * Enthält die zu validierenden Daten
	 * 
	 * @var array
	 */
	private $data          = null;
	
    /**
     * Ist die Form valide oder nicht.
     * 
     * @var boolean
     */
	private $isValid       = null;
	
	/**
	 * Ob die Form validiert wurde oder nicht
	 *
	 * @var boolean
	 */
	private $hasValidated  = false;
	
	/**
	 * Validatornachrichten.
	 * 
	 * @var array
	 */
	private $messages      = null;
	
	private $hasSaved      = null;
	
	/**
	 * Standard DB Tabelle, in die diese Form schreiben wird.
	 * @var unknown
	 */
	private $default_table = null;
	
	/**
	 * Was wird bei erfolgreicher Validierung des Formulares in der Datenbank gemacht?
	 * @var string
	 */
	private $db_mod        = null;
	
	/**
	 * Beinhaltet, den in der DB zu updateten Datensatz.
	 * array: DB-Feld => Form Element
	 * @var unknown
	 */
	private $update_id     = array();
	
	/**
	 * Beinhaltet Datenbankfelder, die nicht über das Formular eingetragen werden.
	 * @var array
	 */
	private $manual_DB_fields  = array();
	

	
	/**
	 * Enthält die Abfragen, die von einer Masterabfrage abhängig ist.
	 * @var array
	 */
	private $dependQueries     = array();
	
	/**
	 * Enthält die bereits ausgeführten Queries samt den DB Results
	 * @var array
	 */
	private $executedQueries   = array();
	
	/**
	 * Enthält die noch auszuführenden Queries.
	 * @var array
	 */
	private $openQueries       = array();
	
	
	
	/**
	 * Legt den Namen der Form fest.
	 *
	 * @param string $name     Name der Form
	 */
	public function __construct($name)
	{
	    $this->setAttribute("name", $name);
	    $this->setAttribute("method", "post");
	}
	
	
    /**
     * (non-PHPdoc)
     * @see \wsc\form\FormInterface::add()
     */
	public function add(ElementInterface $element)
	{
		$this->elements[$element->getAttribute('name')]   = $element;
		return $this;
	}
	
    /**
     * (non-PHPdoc)
     * @see \wsc\form\FormInterface::get()
     */
	public function get($element)
	{
	    if(isset($this->elements[$element]))
	    {
	        return $this->elements[$element];
	    }
	    
	    return null;
	}
	
    /**
     * (non-PHPdoc)
     * @see \wsc\form\FormInterface::setAttribute()
     */
	public function setAttribute($attribute, $value)
	{
	    $this->attributes[$attribute]  = $value;
	}
	
    /**
     * (non-PHPdoc)
     * @see \wsc\form\FormInterface::getAttributes()
     */
	public function getAttributes()
	{
	    return $this->attributes;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \wsc\form\FormInterface::getAttribute()
	 */
	public function getAttribute($attribute)
	{
	    return isset($this->attributes[$attribute]) ? $this->attributes[$attribute] : null;
	}
	
    /**
     * (non-PHPdoc)
     * @see \wsc\form\FormInterface::addValidators()
     */
	public function addValidators($validators)
	{
	    //Wenn Parameter kein Array, wird er zum Array gemacht.
		if(!is_array($validators))
		{
			$validators	= array($validators);
		}
		if(is_array($validators))
		{
			foreach ($validators as $validator)
			{
			    //Wenn der Validator bereits ein gültiges Validatorobjekt ist...
				if($validator instanceof ValidatorInterface)
				{
					$this->validators[]	= $validator;
				}
				else 
				{
				    //...sonst versuche Validatorobjekt aus String zu erstellen.
					$factory	= new ValidatorFactory();
					
					try 
					{
						$validator	= $factory->getValidator($validator);
						$this->validators[] = $validator;
					} 
					catch (\Exception $e) 
					{
						echo nl2br("Exception: ".$e->getMessage()."\n".$e->getTraceAsString());
					}
				}
			}
		}
    }
    
    /**
     * (non-PHPdoc)
     * @see \wsc\form\FormInterface::isValid()
     */
    public function isValid()
    {
        if(!$this->hasValidated)
        {
            $this->validate();
        }
        
        return $this->isValid;
    }
    
    /**
     * (non-PHPdoc)
     * @see \wsc\form\FormInterface::setData()
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }
    
    /**
     * (non-PHPdoc)
     * @see \wsc\form\FormInterface::getData()
     */
    public function getData($element)
    {
        if(isset($this->data[$element]))
        {
            return $this->data[$element];
        }
        
        return null;
    }
    
    /**
     * Validiert die Form.
     */
    private function validate()
    {
        $validation = true;
        $request    = Application::getInstance()->load("request");
        
        if(empty($this->data))
        {
            if($this->getAttribute("method") == "post")
            {
                $this->setData($request->post());
            }
            else
            {
                $this->setData($request->get());
                
            }   
        }
        
        foreach ($this->elements as $element)
        {
            $element_name   = $element->getAttribute("name");
            $element->setData($this->getData($element_name));
            
            if(!$element->isValid())
            {
                $validation = false;
                
                $this->messages[$element_name] = $element->getMessage();
            }
        }
        
        $this->hasValidatet = true;
        $this->isValid      = $validation;
    }
    
    /**
     * (non-PHPdoc)
     * @see \wsc\form\FormInterface::getMessages()
     */
    public function getMessages($element = null)
    {
        if(is_null($element))
        {
            return $this->messages;
        }
        else
        {
            if(isset($this->messages[$this->element->getAttribute("name")]))
            {
                $message    = $this->messages[$this->element->getAttribute("name")];
            }
            else
            {
                $message    = null;
            }
            
            return $message;
        }
    }
    

    public function enableDBFunctions($database)
    {
        $this->database = $database;
    }
    
    /**
     * Stellt die benötigten Daten (Tabellen, Felder, Werte) für das Eintragen der Elemente in die Datenbank bereit.
     * 
     * @return array
     */
    private function getDBDataFromElements()
    {
        //Erklärung array: $db_data['table']    = array('field' => 'value');
        $db_data = array();
        
        foreach ($this->elements as $element)
        {
            if(!empty($element->getTableField()))
            {
                if(!empty($element->getDBTable) || !empty($this->default_table))
                {
                    if(empty($element->getDBTable()))
                    {
                        $db_table   = $this->default_table;
                    }
                    else
                    {
                        $db_table   = $element->getDBTable();
                    }
        
                    $db_data[$db_table][$element->getTableField()]  = $element->getData();
                }
                else
                {
                    echo ("Keine Datenbanktabelle zum Eintrage der Daten eingetragen.");
                }
            }
        }
        
        return $db_data;
    }
    
    /**
     * Stellt die benötigten Daten (Tabellen, Felder, Werte) für das Eintragen der manuellen Daten in die Datenbank bereit.
     *
     * @return array
     */
    private function getManualDBData()
    {
        $db_data    = array();
        
        foreach ($this->manual_DB_fields as $table => $data)
        {
            foreach ($data as $field => $value)
            {
                $db_data[$table][$field]    = $value;
            }
        }
        
        return $db_data;
    }
    
    /**
     * (non-PHPdoc)
     * @see \wsc\form\FormInterface::setUpdateID()
     */
    public function setUpdateID($db_field, $value)
    {
        $this->update_id[$db_field] = $value;
    }
    
    /**
     * (non-PHPdoc)
     * @see \wsc\form\FormInterface::setDefaultTable()
     */
    public function setDefaultTable($table)
    {
        $this->default_table    = $table;
    }
    
    /**
     * (non-PHPdoc)
     * @see \wsc\form\FormInterface::setDBMod()
     */
    public function setDBMod($db_mod)
    {
        if($db_mod == (self::DB_INSERT || self::DB_DELETE || self::DB_SELECT || self::DB_UPDATE))
        {
            $this->db_mod = $db_mod;
        }
        else
        {
            echo "unbekannte DB Modus ausgewaehlt.";
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see \wsc\form\FormInterface::addManualDBField()
     */
    public function addManualDBField($table = null, $field, $value)
    {
        $db_table  = (empty($table)) ? $this->default_table : $table;
        
        if($db_table !== null)
        {
            $this->manual_DB_fields[$db_table][$field] = $value;
        }
    }
    /**
     * Fügt eine abhängige Abfrage hinzu.
     *
     * @param string $table                Tabelle, in die Abfrage eingreift
     * @param string $masterQuery          Abfrage, von der die neue Abfrage abhängig ist.
     * @param string $masterIdTableField   Tabellenfeld in das die ID des Masterdatensatz eingetragen werden soll
     */
    public function addDependQuery($table, $masterQuery, $masterIdTableField)
    {
        $this->dependQueries[$table]['master']              = $masterQuery;
        $this->dependQueries[$table]['masterIdTableField']  = $masterIdTableField;
        $this->dependQueries[$table]['result']              = null;
    }
    
    
    /**
     * Bildet den SQL Querystring für die Elemente, die in die Datenbank eingetragen werden sollen.
     * @param string $method   Methode, die angewandt werden soll. (insert, update, delete)
     * @param string $query    Name der Abfrage = Name der Tabelle
     * @param array $fields['DB Feld']['Einzutragender Wert']
     * @return unknown
     */
    private function createQuery($method, $query, $fields)
    {
        $table     = $query;
        $data      = $fields;
        $statement = "";
    
        switch($method)
        {
        	case self::DB_INSERT:
    
        	    $statement  .= "INSERT INTO " . $table . " (";
    
        	    $loops  = 1;
    
        	    //Wenn es eine abhängige Abfrage ist..
        	    if(array_key_exists($query, $this->dependQueries))
        	    {
        	        //...und die Masterabfrage bereits durchgeführt wurde, dann lesen wir die benötigten Daten der MA aus und fügen die Daten der abhängigen Abfrage an.
        	        if(isset($this->executedQueries[$this->dependQueries[$table]['master']]))
        	        {
        	            $data[$this->dependQueries[$table]['masterIdTableField']] = $this->executedQueries[$this->dependQueries[$table]['master']]['database']->insert_id;
        	        }
        	        else
        	        {
        	            echo "<br>Die abhaengige Abfrage konnte nicht auseführt werden, weil die Masterabfrage noch nicht ausgefuehrt wurde!<br>";
        	        }
        	    }
        	    else
        	    {
        	        //DEBUG: echo "Es wurde die unabhaengige Abfrage ". $query ." bearbeitet.<br>";
        	    }
        	     
        	    $num_fields = count($data);
        	     
        	    foreach ($data as $field => $value)
        	    {
        	        $statement  .= "" . $field . "";
    
        	        if($loops < $num_fields)
        	        {
        	            $statement  .= ",";
        	        }
        	        $loops  += 1;
        	    }
        	     
        	    $loops  = 1;
    
        	    $statement  .= ") VALUES(";
    
        	    foreach ($data as $value)
        	    {
        	        $statement  .= "'" . $value . "'";
    
        	        if($loops < $num_fields)
        	        {
        	            $statement  .= ",";
        	        }
    
        	        $loops  += 1;
        	    }
    
        	    $statement  .= ")";
    
        	    break;
    
        	case self::DB_UPDATE:
        	    break;
    
        	case self::DB_DELETE:
        	    break;
    
        }
        return $statement;
    }
    
    /**
     * Führt die ein SQL Statement aus.
     *
     * @param string $table        Der Name der Abfrage.
     * @param string $statement    Das SQL Statement
     */
    private function executeStatement($table, $statement)
    {
        if($this->database !== null)
        {
            //DEBUG: echo $statement. " wird jetzt ausgefuehrt...<br>";
             
            $res     = $this->database->query($statement) or die($this->database->error);
            $result  = array("result"    => $res, "database"   => $this->database);
            $this->executedQueries[$table] = $result;
        }
        else
        {
            echo "Es wurde keine Datenbankverbindung uebergeben. Die Form Daten koennen nicht geschrieben werden.";
        }
    
    }
    
    /**
     * (non-PHPdoc)
     * @see \wsc\form\FormInterface::executeDatabase()
     */
    public function executeDatabase()
    {
        $this->openQueries  = $this->getDBDataFromElements();
        $db_data_manual     = $this->getManualDBData();
         
        //Manuelle DB Felder zu den offenen Queries hinzufügen
        foreach ($db_data_manual as $table =>  $data_manual)
        {
            foreach ($data_manual as $field => $value)
            {
                $this->openQueries[$table][$field]  = $value;
            }
        }
        //Zuerst alle Queries ausführen, die von keinem anderen Query abhängig sind...
        $this->runOpenQueries(false);
        //...dann alle verbleibenden Queries ausführen (mit Abhängigkeit).
        $this->runOpenQueries(true);
         
        return true;
    }
    
    /**
     * Führt die noch nicht ausgeführten Queries aus.
     *
     * @param bool $dependQueries      Steuert, ob die Abhängigen Abfragen mitausgeführt werden sollen.
     */
    private function runOpenQueries($dependQueries = false)
    {
        $queries   = $this->openQueries;
         
         
        if($dependQueries  === false)
        {
            //Abhängige Queries herausnehmen
            foreach ($queries as $query => $fields)
            {
                if(array_key_exists($query, $this->dependQueries))
                {
                    unset($queries[$query]);
                }
            }
        }
        //Queries ausführen und von den offenen Queries entfernen.
        foreach ($queries as $query => $fields)
        {
            $statement = $this->createQuery($this->db_mod, $query, $fields);
            $this->executeStatement($query, $statement);
            unset($this->openQueries[$query]);
        }
    }
}
?>