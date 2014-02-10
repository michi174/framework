<?php
namespace wsc\form\view\helpers;

use wsc\form\FormInterface;

class form extends AbstractFormHelper
{
    /**
     * Gibt den �ffnenden Tag der Form zur�ck.
     * 
     * @param FormInterface $form
     * @return string
     */
    public function openTag(FormInterface $form)
    {
        return $this->render($form);
    }
    
    /**
     * Gibt den schlie�enden HTML-Tag der Form zur�ck.
     * @return string
     */
    public function closeTag()
    {
        return "</form>";
    }
    
    /**
     * Rendert den �ffnenden HTML Tag der Form.
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