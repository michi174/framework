<?php
namespace wsc\form\element;

use wsc\form\view\helpers\FormSelect;
/**
 *
 * @author michi_000
 *        
 */
class Select extends Element
{
    protected $select_options       = array();
    protected $option_attr          = array();
    
    
    public function __construct($name)
    {
        parent::__construct($name);
    }
    
    /**
     * Fόgt dem Select Element eine Option hinzu.
     * 
     * @param string $name
     * @param string $display_name
     */
    public function addOption($name, $display_name = null)
    {
        if(is_null($display_name))
        {
            $display_name   = $name;
        }
        
        $this->select_options[$name]   = $display_name;
        $this->setOptionAttribute($name, "value", $name);
        
        return $this;
    }
    
    public function addOptions(array $options)
    {
        foreach ($options as $label => $value)
        {
            $this->addOption($value, $label);
        }
    }
    
    public function addOptionsFromDBQuery($options, $value_field, $label_field)
    {
        $new_options    = array();
        
        if(is_array($options))
        { 
            foreach ($options as $option)
            {
                $new_options[$option[$label_field]] = $option[$value_field];
            }
            
            $this->addOptions($new_options);
        }
        else
        {
            $this->addOption(0, "Keine Optionen verf&uuml;gbar");
            $this->setOptionAttribute(0, "disabled", "disabled");
        }
    }
    
    /**
     * Fόgt der Select Option ein Attribut hinzu.
     * 
     * @param string $option
     * @param string $attribute
     * @param string $value
     */
    public function setOptionAttribute($option, $attribute, $value = "")
    { 
        $this->option_attr[$option][$attribute] = $value;
    }
    
    /**
     * Gibt alle Attribute der Option zurόck.
     * 
     * @param string $option
     * @return array
     */
    public function getOptionAttributes($option)
    {
        return $this->option_attr[$option];
    }
    
    /**
     * Gibt alle Optionen des Select Elementes zurόck.
     * @return array
     */
    public function getOptions()
    {
        return $this->select_options;
    }
    
    /**
     * Setzt die standardmδίig markierte Option.
     * @param string $option
     */
    public function setDefaultOption($option)
    {
        //Prόfen, ob bereits eine default option gesetzt ist, wenn erforderlich entfernen.
        foreach ($this->select_options as $old_default => $name)
        {
            if(isset($this->option_attr[$old_default]['selected']))
            {
                unset($this->option_attr[$old_default]['selected']);
            }
        }
        
        //Neue Default Option definieren
        $this->setOptionAttribute($option, "selected", "selected");
        return $this;
    }
    
    /**
     * άberschreibt die Methode zum Schreiben der AutoValue, da fόr Select Elemente,
     * nicht die Value verδndert wird, sondern der selected Attribut verwendet wird.
     * 
     * @see \wsc\form\element\Element::writeAutoValue()
     */
    protected function writeAutoValue()
    {        
        if(isset($this->select_options[$this->getData()]))
        {
            $this->setDefaultOption($this->getData());
        }
    }
    
    public function getDefaultViewHelper()
    {
        return new FormSelect();
    }
}

?>