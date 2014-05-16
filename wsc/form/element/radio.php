<?php
namespace wsc\form\element;

/**
 *
 * @author michi_000
 *        
 */
class Radio extends Element
{
    private $radios = array();
    private $radio_attr = array();
    
    public function __construct($name)
    {
        parent::__construct($name);
        
        $this->setAttribute("type", "radio");
    }
    
    public function addRadio($id, $value, $label)
    {
        $this->radios[$id]   = $label;
        $this->setRadioAttribute($id, "value", $value);
        $this->setRadioAttribute($id, "id", $id);
        
        return $this;
    }
    
    public function setActive($id)
    {
        foreach ($this->radios as $radio => $value)
        {
            if(isset($this->radio_attr[$radio]["checked"]))
            {
                unset($this->radio_attr[$radio]["checked"]);
            }
        }
        $this->setRadioAttribute($id, "checked", "checked"); 
        return $this;
    }
    
    public function setRadioAttribute($radio, $attribute, $value)
    {
        $this->radio_attr[$radio][$attribute]   = $value;
        return $this;
    }
    
    public function getRadios()
    {
        return $this->radios;
    }
    
    public function getRadioAttributes($radio)
    {
        return $this->radio_attr[$radio];
    }
    
    protected function writeAutoValue()
    {
        
    }
    
    public function getRadio($radio)
    {
        if(isset($this->radios[$radio]))
        {
            return $this->radios[$radio];
        }
        
        return null;
    }
    
    public function getRadioAttribute($radio, $attribute)
    {
        if(isset($this->radio_attr[$radio][$attribute]))
        {
            return $this->radio_attr[$radio][$attribute];
        }
        
        return null;
    }
}

?>