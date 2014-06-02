<?php

namespace wsc\form;

use wsc\validator\ValidatorInterface;
use wsc\validator\ValidatorFactory;
use wsc\form\element\ElementInterface;
use wsc\application\Application;
use wsc\database\Database;

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
	 * Legt den Namen der Form fest.
	 * 
	 * @param string $name     Name der Form
	 */
	public function __construct($name)
	{
	    $this->setAttribute("name", $name);
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
    
    /**
     * Datenbankfunktionen aktivieren.
     * Erfordert eine Datenbankverbindung als Paramenter.
     * 
     * @param Database $database
     */
    public function enableDBFunctions($database)
    {
        $this->database = $database;
    }
    
    /**
     * Bei führt den ausgewählten Befehl in der Datenbank aus.
     */
    public function executeDatabase()
    {
        if($this->database !== null)
        {
            //$db_data['table']    = array('field' => 'value');
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
            
            foreach ($this->manual_DB_fields as $table => $data)
            {
                foreach ($data as $field => $value)
                {
                    $db_data[$table][$field]    = $value;
                }
            }
            
            switch ($this->db_mod)
            {
            	case self::DB_INSERT:
            	    
            	    $statements    = array();
            	    foreach ($db_data as $table => $data)
            	    {
                        $statement  = "INSERT INTO " . $table . " (";
                        
                        $num_fields = count($data);
                        $loops  = 1;
                        
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
                        
                        $statements[]   = $statement;
            	    }
            	    foreach ($statements as $statement)
            	    {
            	        $res   = $this->database->query($statement) or die($this->database->error);
            	    }
            	    
            	    return $res;
            	    
            	break;
            	
            	
            }
            
        }
        else
        {
            echo "Es wurde keine Datenbankverbindung übergeben. Die Form Daten können nicht geschrieben werden.";
        }
        
        
    }
    
    public function setUpdateID($db_field, $value)
    {
        $this->update_id[$db_field] = $value;
    }
    
    public function setDefaultTable($table)
    {
        $this->default_table    = $table;
    }
    
    public function setDBMod($db_mod)
    {
        if($db_mod == (self::DB_INSERT || self::DB_DELETE || self::DB_SELECT || self::DB_UPDATE))
        {
            $this->db_mod = $db_mod;
        }
    }
    
    public function addManualDBField($table = null, $field, $value)
    {
        if(empty($table))
        {
            if(!empty($this->default_table))
            {
                $table  = $this->default_table;
                $this->manual_DB_fields[$table][$field] = $value;
            }
        }
    }
}
?>