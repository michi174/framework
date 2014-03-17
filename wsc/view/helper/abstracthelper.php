<?php
namespace wsc\view\helper;

/**
 * Abstrakter ViewHelper, der die Basisfunktionen für ViewHelper bereitstellt.
 * 
 * @author Michi
 *        
 */
abstract class AbstractHelper implements HelperInterface
{
    /**
     * Parameter für die benötigte Klasse.
     * @var array
     */
    protected $params   = array();
    
    /**
     * Legt die Parameter fest, die bei Aufruf einer unbekannten Klasse erfolgt.
     *
     * @param mixed $params     Parameter, für den Konstruktor der benötigten Klasse.
     */
    public function __construct($params = NULL)
    {
        if(!is_null($params))
        {
            $this->params   = $params;
        }
    }
    
    /**
     * Gibt an, was der ViewHelper ausgeben soll, wenn er im ViewScript aufgerufen wird.
     * 
     * @return mixed
     */
    public function __toString()
    {
        return self::TO_STRING_NOT_ALLOWED;
    }
}

?>