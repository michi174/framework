<?php

namespace wsc\form\element;

use wsc\validator\ValidatorChain;
use wsc\validator\NotEmpty;
use wsc\validator\ValidatorInterface;
use wsc\validator\ValidatorFactory;
use wsc\form\view\helpers\FormElement;
/**
 *
 * @author Michi
 *        
 */
class Element implements ElementInterface
{
    /**
     * Attributes des Elements
     * @var array
     */
	private $attributes    = array();
	
	/**
	 * Übermittelte Daten für das Element
	 * @var mixed
	 */
	protected $data          = null;
	
	/**
	 * Validatornachrichten für das Element.
	 *
	 * @var string
	 */
	private $messages      = null;
	
	/**
	 * Validatoren für das Element.
	 * @var array
	 */
	private $validators    = array();
	
	/**
	 * Optionen für das Element.
	 * @var array
	 */
	private $options     = array(
	    'error_class'  => 'input-error',
	);
	
	/**
	 * Steuert ob die übermittelten Daten als Value eingesetzt werden oder nicht.
	 * @var unknown
	 */
	private $autovalue   = true;
	
	/**
	 * Hat das Element einen Validierungsfehler oder nicht.
	 * @var boolean
	 */
	private $hasError      = false;
    
	/**
	 * Feld in der DB Tabelle, das befüllt wird.
	 * @var array
	 */
	private $table_field   = array();
	
	/**
	 * @param string $element_name     Name des zu erstellenden Elements
	 */
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
		return $this;
	}
	
	/**
	 * Legt ein Label für das Element fest.
	 * 
	 * @param string $label		Label
	 */
	public function setLabel($label)
	{
		$this->options['label']	= $label;
		
		return $this;
	}
	
	/**
	 * Gibt das Label zurück. Wird der HTML Parameter auf true gesetzt, wird ein Label Tag erzeugt.
	 * 
	 * @return string
	 */
	public function getLabel()
	{
		if(!isset($this->attributes['id']))
		{
			$this->setAttribute("id", $this->attributes['name']);
		}
			
		if(isset($this->options['label']))
		{
		    return $this->options['label'];
		}
		
		return null;

	}
	
	/**
	 * Gibt den Inhalt des Feldes zurück.
	 * 
	 * @return mixed $value		Der Inhalt des Elements
	 */
	public function getValue()
	{
		if(isset($this->attributes['value']))
		{
			return $this->attributes['value'];
		}
		
		else 
		{
			return null;
		}
	}
	
	/**
	 * Prüft, ob das Element gültig ist.
	 * 
	 * @return boolean
	 */
	public function isValid()
	{
		if(!is_null($this->data))
		{
			$chain		= new ValidatorChain();
			
			foreach ($this->validators as $validator)
			{
				$chain->add($validator);
			}
			
			if(!$chain->isValid($this->getData()))
			{
			    $this->messages  = $chain->getMessage();
			    $this->hasError  = true;
			    $this->setAttribute("class", $this->getAttribute("class"). " " . $this->options['error_class']);
				return false;
			}
			
			if($this->autovalue === true)
			{
			    $this->writeAutoValue();
			}
			
		}
		
		return true;
	}
	
	/**
	 * Schreibt die übermittelten Daten als Value in das Element.
	 * 
	 * Damit wird erreicht, dass der eingebene Wert nach einer möglicherweiße fehlerhaften
	 * Validierung auch nach dem Absenden noch im Input Feld angezeigt wird.
	 */
	protected function writeAutoValue()
	{
	    $this->setAttribute("value", $this->getData());
	}
	
	/**
	 * Fügt dem Element einen Validator hinzu.
	 * 
	 * @param multitype: ValidatorInterface | array | string $validator
	 */
	public function addValidator($validators)
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
	    return $this;
	}
	
	/**
	 * Macht das Element zu einem Pflichtfeld.
	 */
	public function setRequired()
	{
		$this->setAttribute("required", "required");
		$this->addValidator(new NotEmpty(array(NotEmpty::STRING, NotEmpty::INT, NotEmpty::FLOAT)));
		return $this;
	}
    /**
     * @see \wsc\form\element\ElementInterface::getAttributes()
     */
	public function getAttributes()
	{
	    return $this->attributes;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \wsc\form\element\ElementInterface::getAttribute()
	 */
	public function getAttribute($attribute)
	{
	    return isset($this->attributes[$attribute]) ? $this->attributes[$attribute] : null;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \wsc\form\element\ElementInterface::setData()
	 */
	public function setData($data)
	{
	    $this->data    = $data;
	    return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \wsc\form\element\ElementInterface::getData()
	 */
	public function getData()
	{
	    return $this->data;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \wsc\form\element\ElementInterface::getMessage()
	 */
	public function getMessage()
	{
	    return $this->messages;
	}
	
	/**
	 * Steuert, ob nach dem Absenden des Formulares der übermittelte Inhalt,
	 * oder der Standardinhalt im Formularfeld angezeigt wird.
	 * 
	 * Übermittelter Inhalt = true
	 * Standardinhalt = false
	 * 
	 * @param boolean $value
	 */
	public function setAutoValue($value)
	{
	    if(is_bool($value))
	    {
	        $this->autovalue   = $value;
	    }
	    
	    return $this;
	}
	
	/**
	 * Weist dem Element einen Anzeigenamen zu.
	 * 
	 * @param string $name
	 * @return \wsc\form\element\Element
	 */
	public function setDisplayName($name)
	{
	    $this->options["displayname"]  = $name;
	    return $this;
	}
	
	/**
	 * Gibt den Anzeigenamen des Elementes zurück.
	 * 
	 * @return string
	 */
	public function getDisplayName()
	{
	    if(isset($this->options["displayname"]))
	    {
	        return $this->options["displayname"];
	    }
	    else
	        return $this->getAttribute("name");
	}
	
	/**
	 * Gibt zurück, ob das Feld einen Fehler bei der Validierung hatte.
	 * 
	 * @return boolean
	 */
	public function hasError()
	{
	    return $this->hasError;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \wsc\form\element\ElementInterface::setTableField()
	 */
	public function setTableField($field, $table = null)
	{
	    $this->options['db_table_field']   = $field;
	    $this->options['db_table']         = $table;
	    
	    return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \wsc\form\element\ElementInterface::getTableField()
	 */
	public function getTableField()
	{
	    if(isset($this->options['db_table_field']))
	    {
	       return $this->options['db_table_field'];
	    }
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \wsc\form\element\ElementInterface::getDBTable()
	 */
	public function getDBTable()
	{
	    return $this->options['db_table'];
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \wsc\form\element\ElementInterface::getDefaultViewHelper()
	 */
	public function getDefaultViewHelper()
	{
	    return new FormElement();
	}
}

?>