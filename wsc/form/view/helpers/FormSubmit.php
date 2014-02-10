<?php
namespace wsc\form\view\helpers;

use wsc\form\view\helpers\FormInput;
use wsc\form\element\ElementInterface;

/**
 *
 * @author Michi
 *        
 */
class FormSubmit extends FormInput
{
    protected function getType(ElementInterface $element)
    {
        return "submit";
    }
}

?>