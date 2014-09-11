<?php
namespace wsc\view\helper;

use wsc\application\Application;
/**
 *
 * @author michi_000
 *        
 */
class Currenturl extends AbstractHelper
{
    public function getCurrentURL()
    {
        $app    = Application::getInstance();
    }
}

?>