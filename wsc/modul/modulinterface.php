<?php
namespace wsc\modul;

/**
 *
 * @author Michi
 *        
 */
interface ModulInterface
{
    public function getClass();
    public function registerAutoloader();
}

?>