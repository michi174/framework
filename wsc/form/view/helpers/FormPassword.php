<?php
namespace wsc\form\view\helpers;

use wsc\form\element\ElementInterface;
/**
 *
 * @author Michi
 *        
 */
class FormPassword extends FormInput
{
    protected function getType(ElementInterface $element)
    {
        return "password";
    }
}

?>