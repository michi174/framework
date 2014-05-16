<?php
namespace wsc\form\view\helpers;

use wsc\form\element\ElementInterface;
/**
 *
 * @author michi_000
 *        
 */
class FormRadio extends AbstractFormHelper
{
    private $element    = null;
    
    public function render(ElementInterface $element)
    {
        $this->element  = $element;
        return $this;
    }
    
    public function get($radio)
    {
        $this->render(current($this->params));
        
        $left   = null;
        $right  = null;
        
        return $left."<input " . $this->buildAttrString($this->element->getAttributes()) . " " . $this->buildAttrString($this->element->getRadioAttributes($radio)) . ">".$this->renderLabel($radio);
    }
    
    public function renderLabel($radio)
    {
        return "<label for=\"" . $this->element->getRadioAttribute($radio, "id") . "\">&nbsp;" . $this->element->getRadio($radio) . "</label>";
    }
}

?>