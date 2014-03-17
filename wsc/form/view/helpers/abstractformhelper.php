<?php
namespace wsc\form\view\helpers;

use wsc\view\helper\AbstractHelper;
use wsc\form\element\ElementInterface;

/**
 * Abstrakter ViewHelper für Form Elemente.
 * 
 * @author michi_000
 *
 */
abstract class AbstractFormHelper extends AbstractHelper
{
    const INVALID_DATA_TYPE = "canNotCreateFormElement";
    
    /**
     * Rendert das aktuelle Element der Form und gibt es zurück.
     * 
     * @see \wsc\view\helper\AbstractHelper::__toString()
     */
    public function __toString()
    {
        if(current($this->params) instanceof ElementInterface)
        {
            return $this->render(current($this->params));
        }
        else
        {
            return self::INVALID_DATA_TYPE;
        }
    }
    
    /**
     * Erzeugt einen String aller Attribute aus einem Array.
     * 
     * @param array $attributes
     * @return string
     */
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