<?php
namespace wsc\form\view\helpers;

use wsc\form\FormInterface;

class form extends AbstractFormHelper
{
    /**
     * Gibt den öffnenden Tag der Form zurück.
     * 
     * @param FormInterface $form
     * @return string
     */
    public function openTag(FormInterface $form)
    {
        return $this->render($form);
    }
    
    /**
     * Gibt den schließenden HTML-Tag der Form zurück.
     * @return string
     */
    public function closeTag()
    {
        return "</form>";
    }
    
    /**
     * Rendert den öffnenden HTML Tag der Form.
     * 
     * @param FormInterface $form
     * @return string
     */
    private function render(FormInterface $form)
    {
        return "<form ". $this->buildAttrString($form->getAttributes()) . ">";
    }
}
?>