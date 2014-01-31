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
    
    public function __construct($params = NULL)
    {
        if(!is_resource($params))
        {
            $this->content  = implode("", $params);
        }
    }
    
    public function addQuotes($content = NULL)
    {
        if(!is_null($content))
        {
            $this->content  = $content;
        }
        
        return "&quot;".$this->content."&quot";
    }
    
    public function __toString()
    {
        return $this->addQuotes();
    }
}

?>