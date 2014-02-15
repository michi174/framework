<?php
namespace wsc\form\view\helpers;

use wsc\form\element\ElementInterface;

class formHidden extends FormInput
{
    protected function getType(ElementInterface $element)
    {
        return "hidden";
    }
}

?>