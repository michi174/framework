<?php
namespace wsc\view\helper;

/**
 *
 * @author Michi
 *        
 */
abstract class AbstractHelper implements HelperInterface
{
    protected $params   = array();
    
    public function __construct($params=NULL)
    {
        if(!is_null($params))
        {
            $this->params   = $params;
        }
    }
    
    public function __toString()
    {
        return self::TO_STRING_NOT_ALLOWED;
    }
}

?>