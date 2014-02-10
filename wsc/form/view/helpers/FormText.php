<?php
namespace wsc\form\view\helpers;

use wsc\form\element\ElementInterface;
/**
 *
 * @author Michi
 *        
 */
class FormText extends FormInput
{
    protected function getType(ElementInterface $element)
    {
        return "text";
    }
}

?>