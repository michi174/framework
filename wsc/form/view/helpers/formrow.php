<?php
namespace wsc\form\view\helpers;

use wsc\form\element\ElementInterface;
/**
 * 
 * @author michi_000
 *
 */
class FormRow extends AbstractFormHelper
{
    const APPEND    = "append";
    const PREPEND   = "prepend";
    
    private $error_class        = "input-error";
    private $label_pos          = self::PREPEND;
    
    private $elemtent_helper    = null;
    private $label_helper       = null;
    private $error_helper       = null;
    
    public function __construct($params = NULL)
    {
        parent::__construct($params);
        
        $this->getElementHelper();
        $this->getLabelHelper();
        $this->getErrorHelper();
    }
    
    /**
     * Gibt eine Zeile mit ferig gerendertem Element, Label und Fehlernachricht (wenn vorhanden) zurück.
     * 
     * @param ElementInterface $element     Das Element, für das die Zeile erstellt werden soll.
     * @return string
     */
    public function render(ElementInterface $element)
    {
        $row    = array();
        
        //Element hinzufügen
        $row[]  = $this->elemtent_helper->render($element);
        
        //Label hinzufügen
        if($element->getLabel() !== null)
        {
            $label  = $this->label_helper->render($element);
            
            if($this->label_pos === self::PREPEND)
            {
                array_unshift($row, $label);
            }
            else
            {
                array_push($row, $label);
            }
        }
        
        //Fehlernachrichten hinzufügen
        $row[]  = $this->error_helper->render($element);
        
        return implode("", $row);
    }
    
    /**
     * Definiert die Position des Labels. 
     * Mögliche Optionen sind "prepend" und "append".
     * 
     * @param string $position
     * @return \wsc\form\view\helpers\FormRow
     */
    public function setLabelPosition($position)
    {
        $this->label_pos    = $position;
        
        return $this;
    }
    
    /**
     * Holt den Element Helper, der das Element generieren kann.
     */
    private function getElementHelper()
    {
        $this->elemtent_helper  = new FormElement();
    }
    
    /**
     * Holt den Label Helper, der das Label generieren kann.
     */
    private function getLabelHelper()
    {
        $this->label_helper     = new FormLabel();
    }
    
    /**
     * Holt den Error Helper, der die Fehlernachrichten generieren kann.
     */
    private function getErrorHelper()
    {
        $this->error_helper     = new FormElementError();
    }
}

?>