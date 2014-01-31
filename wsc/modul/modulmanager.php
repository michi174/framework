<?php

namespace wsc\modul;

use wsc\config\Config;
use wsc\modul\ModulInterface;
/**
 *
 * @author Michi
 *        
 */
class ModulManager 
{
    private $moduls	=   array();
	
    /**
	 * Registriert ein neues Modul
	 * 
	 * @param string $modul
	 * @throws \Exception
	 */
	public function register($modul)
	{
		$modul_path	= $this->getModulPath($modul);
		
		if(file_exists($modul_path))
		{
		    require_once $modul_path. "/" .$modul. ".php";
		    
		    $class    = $modul."\\".$modul;
		    $object   = new $class;
		    
		    if($object instanceof ModulInterface)
		    {
		        $object->register_autoloader();
		        $this->addModule($modul, $object);
		    }
		    else
		    {
		        throw new \Exception("Das Modul " . $modul . " konnte nicht registriert werden (muss vom Typ ModulInterface sein).");
		    }
		}
		else 
		{
		    throw new \Exception("Das Modul " . $modul . " konnte nicht registriert werden. (Datei nicht gefunden");
		}
	}
	
	/**
	 * 
	 * @param string           $name       Name des Moduls
	 * @param ModulInferface   $object     Object des Moduls
	 */
	private function addModule($name, $object)
	{
	    $this->moduls[$name]   = $object;
	}
	
	/**
	 * Gibt ein oder mehrere Module zurück.
	 * 
	 * Wird der Parameter modul übergeben, wird das angeforderte Modul zurückgeben.
	 * Bleib der Parameter auf null, wird ein Array mit allen Modulen zurückgegeben.
	 * 
	 * @param string $modul
	 * @return multitype: ModulInterface | array | boolean
	 */
	public function get($modul = null)
	{
		if(!is_null($modul) && array_key_exists($modul,$this->moduls))
		{
            return $this->moduls[$modul];
		}
		
		if(is_null($modul))
		{
            return $this->moduls;
		}
		
		return false;
	}
	
	/**
	 * Überprüft ob das Modul gültig ist.
	 * 
	 * @param string $modul
	 */
	private function checkModul($modul)
	{
		
	}
	
	/**
	 * Gibt den Pfad zum Modul zurück
	 * 
	 * @param ModulInterface $modul		Objekt des Moduls
	 * @return string
	 */
	private function getModulPath($modul)
	{
        return strtolower(Config::getInstance()->get("doc_root")."/".Config::getInstance()->get("project_dir")."/".Config::getInstance()->get("modul_dir")."/".$modul);
	}
}

?>