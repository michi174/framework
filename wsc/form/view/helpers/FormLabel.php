<?php
namespace wsc\form\view\helpers;

use wsc\form\element\ElementInterface;
/**
 *
 * @author michi_000
 *        
 */
class FormLabel extends AbstractFormHelper
{
    private $attributes = array();
    
    public function render(ElementInterface $element)
    {
        $this->attributes['for']    = $element->getAttribute("id");
        
        $attributes = $this->buildAttrString($this->attributes);
        
        return "<label ".$attributes.">".$element->getLabel()."</label>";
    }
}

?>