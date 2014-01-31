<?php
namespace wsc\form\view\helpers;

use wsc\view\helper\AbstractHelper;

/**
 *
 * @author Michi
 *        
 */
abstract class AbstractFormHelper extends AbstractHelper
{
    protected function buildAttrString(array $attributes)
    {
        $attr_strings   = array();
        
        foreach ($attributes as $attribute => $value)
        {
            if($value)
            {
                $attr_strings[]  = strtolower($attribute). "=\"" . $value . "\" ";
            }
            else
            {
                continue;
            }
        }
        return implode(" ", $attr_strings);
    }
}

?>