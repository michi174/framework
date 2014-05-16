<?php
namespace wsc\form\view\helpers;

use wsc\form\element\ElementInterface;
/**
 *
 * @author michi_000
 *        
 */
class FormSelect extends AbstractFormHelper
{
    public function render(ElementInterface $element)
    {
        if(is_null($element->getAttribute("id")) || $element->getAttribute("id") == "" )
        {
            $element->setAttribute("id", $element->getAttribute("name"));
        }
        
        $select = "<select ". $this->buildAttrString($element->getAttributes()).">";
        $select .= $this->renderOptions($element);
        $select .= "</select>";
        
        return $select;
        
    }
    
    public function renderOptions(ElementInterface $element)
    {
        $options        = $element->getOptions();
        $option_str     = null;
        
        foreach ($options as $option => $option_disp_name)
        {
            $option_str     .= "<option " . $this->buildAttrString($element->getOptionAttributes($option)) . ">" . $option_disp_name . "</option>";
        }
        return $option_str;
    }
}

?>