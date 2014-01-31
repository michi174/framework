<?php
namespace wsc\modul;

/**
 *
 * @author Michi
 *        
 */
abstract class AbstractModul implements ModulInterface
{
    /**
     * (non-PHPdoc)
     *
     * @see \wsc\modul\ModulInterface::getClass()
     *
     */
    public function getClass()
    {
    	return __CLASS__;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \wsc\modul\ModulInterface::register_autoloader()
     *
     */
    abstract public function registerAutoloader();
    
}

?>