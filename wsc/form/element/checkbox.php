<?php
namespace wsc\form\element;

/**
 *
 * @author michi_000
 *        
 */
class Checkbox extends Element
{
    public function __construct($name)
    {
        parent::__construct($name);
        $this->setAttribute("type", "checkbox");
    }
    
    /**
     * Aktiviert die Checkbox
     * 
     * @return \wsc\form\element\checkbox
     */
    public function setActive()
    {
        $this->setAttribute("checked", "checked");
        return $this;
    }
    
    /**
     * (non-PHPdoc)
     * @see \wsc\form\element\Element::writeAutoValue()
     */
    protected function writeAutoValue()
    {
        if($this->getData() == "on")
        {
            $this->setActive();
        }
    }
}

?>