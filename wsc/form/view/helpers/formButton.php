<?php
namespace wsc\form\view\helpers;

use wsc\form\element\ElementInterface;
/**
 *
 * @author michi_000
 *        
 */
class FormButton extends FormInput
{
    protected function getType(ElementInterface $element)
    {
        return "Button";
    }
}

?>