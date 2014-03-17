<?php
namespace wsc\view\helper;

/**
 *
 * @author Michi
 *        
 */
class addQuotes extends AbstractHelper
{
    private $content;
    
    /**
     * Fügt dem übergebenen Inhalt Anführungszeichen hinzu.
     * 
     * @param string $content   Zu bearbeitender Inhalt.
     * @return string
     */
    public function addQuotes($content = NULL)
    {
        if(!is_resource($this->params))
        {
            $this->content  = implode("", $this->params);
        }
        
        if(!is_null($content))
        {
            $this->content  = $content;
        }
        
        return "&quot;".$this->content."&quot";
    }
    
    /**
     * Bei Aufruf des ViewHelpers wird die Funktion addQuotes aufgerufen und zurückgegeben.
     * 
     * @see \wsc\view\helper\AbstractHelper::__toString()
     */
    public function __toString()
    {
        return $this->addQuotes();
    }
}

?>