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
        return "<input " . $this->buildAttrString($element->getAttributes()) . " >";
    }
   
    public function __toString()
    {
        if(current($this->params) instanceof ElementInterface)
        {
            return $this->render(current($this->params));
        }
        else 
        {
            die(var_dump(current($this->params)));
            return self::TO_STRING_NOT_ALLOWED;
        }
    }
}

?>