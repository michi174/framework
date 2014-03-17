<?php
namespace wsc\form\view\helpers;

use wsc\form\element\ElementInterface;
/**
 *
 * @author Michi
 *        
 */
class FormInput extends AbstractFormHelper
{
 
    public function render(ElementInterface $element)
    {
        if(is_null($element->getAttribute("id")) || $element->getAttribute("id") == "" )
        {
            $element->setAttribute("id", $element->getAttribute("name"));
        }
        
        $element->setAttribute("type", $this->getType($element));
        return "<input " . $this->buildAttrString($element->getAttributes()) . " >";
    }
    
    protected function getType(ElementInterface $element)
    {
        return $element->getAttribute("type");
    }
}
?>