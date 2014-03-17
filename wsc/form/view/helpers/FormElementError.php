<?php
namespace wsc\form\view\helpers;

use wsc\form\element\ElementInterface;

/**
 * 
 * @author michi_000
 *
 */
class FormElementError extends AbstractFormHelper
{
    public function render(ElementInterface $element)
    {
        if($element->hasError())
        {
            return implode("<br>", $element->getMessage());
        }
        
        return null;
    }
}

?>