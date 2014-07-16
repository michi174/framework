<?php
namespace wsc\form\view\helpers;

use wsc\form\element\ElementInterface;
/**
 *
 * @author michi_000
 *        
 */
class formTextarea extends AbstractFormHelper
{
    public function render(ElementInterface $element)
    {
        $attr   = $this->buildAttrString($element->getAttributes());
        $html   = "<textarea ". $attr . ">" . $element->getData() . "</textarea>";
        
        return $html;
    }
}

?>