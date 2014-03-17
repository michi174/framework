<?php
namespace wsc\form\view\helpers;

use wsc\form\element\ElementInterface;

class FormHidden extends FormInput
{
    protected function getType(ElementInterface $element)
    {
        return "hidden";
    }
}

?>