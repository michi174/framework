<?php

namespace wsc\form;

use wsc\validator\ValidatorInterface;
use wsc\validator\ValidatorFactory;
use wsc\form\element\ElementInterface;
use wsc\application\Application;
/**
 *
 * @author Michi
 *        
 */
class Form implements FormInterface
{
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
	
	private $messages      = null;
	
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
	 * Fügt der Form ein Element hinzu.
	 * 
	 * @param Element $element
	 */
	public function add(ElementInterface $element)
	{
		$this->elements[$element->getAttribute('name')]   = $element;
		return $this;
	}
	
	/**
	 * Gibt ein Element der Form zurück.
	 * 
	 * @param string $name     Name des Elements
	 * @return multitype:ElementInterface|NULL
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
	 * Fügt einen Attribut hinzu.
	 * 
	 * @param string $attribute    Attributname
	 * @param string $value        Inhalt des Attributes
	 */
	public function setAttribute($attribute, $value)
	{
	    $this->attributes[$attribute]  = $value;
	}
	
	/**
	 * Gibt alle Attribute einer Form zurück
	 * 
	 * @see \wsc\form\FormInterface::getAttributes()
	 */
	public function getAttributes()
	{
	    return $this->attributes;
	}
	
	public function getAttribute($attribute)
	{
	    return isset($this->attributes[$attribute]) ? $this->attributes[$attribute] : null;
	}
	
	/**
	 * Fügt der gesamten Form einen oder mehrere Validatoren hinzu.
	 * 
	 * @param multitype:string|array|ValidatorInterface $validators
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
     * Gibt zurück ob die Form valide ist oder nicht.
     * 
     * @return boolean
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
     * Legt den zu überprüfenden Inhalt der Form fest.
     * Meißt ein ($_POST Array).
     * 
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }
    
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
}
?>