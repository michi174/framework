<?php
namespace wsc\view\helper;

use wsc\application\Application;
/**
 *
 * @author michi_000
 *        
 */
class CreateUrlString extends AbstractHelper
{
    public function getUrlString($controller, $action = null)
    {
        $config = Application::getInstance()->load("config");
        if(!empty($controller))
        {
            $string = "?".$config->get("ControllerName")."=".$controller;
            $action_str = (!is_null($action)) ? "&".$config->get("ActionName")."=".$action : "";
            $string .= $action_str;
            
            return $string;
        }
        
        return false;
    }
    
    public function __toString()
    {
        return $this->getUrlString($this->params[0], $this->params[1]);
    }
}

?>