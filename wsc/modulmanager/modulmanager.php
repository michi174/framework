<?php

namespace modulmanager;

use wsc\config\Config;
/**
 *
 * @author Michi
 *        
 */
class ModulManager 
{
	private $moduls	= array();
	
	/**
	 * Registriert ein neues Modul
	 * 
	 * @param string $modul
	 */
	public function register($modul)
	{
		$modul_path	= $this->getModulPath($modul);
		
		if(file_exists($modul_path))
		{
			
		}
	}
	
	/**
	 * Gibt ein oder mehrere Module zur�ck.
	 * 
	 * Wird der Parameter modul �bergeben, wird das angeforderte Modul zur�ckgeben.
	 * Bleib der Parameter auf null, wird ein Array mit allen Modulen zur�ckgegeben.
	 * 
	 * @param string $modul
	 * @return multitype:|boolean
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
	 * �berpr�ft ob das Modul g�ltig ist.
	 * 
	 * @param string $modul
	 */
	private function checkModul($modul)
	{
		
	}
	
	/**
	 * Gibt den Pfad zum Modul zur�ck
	 * 
	 * @param string $modul		Name des Moduls
	 * @return string
	 */
	private function getModulPath($modul)
	{
		return strtolower(Config::getInstance()->get("doc_root")."/".Config::getInstance()->get("project_dir")."/".Config::getInstance()->get("modul_dir")."/".$modul);
	}
}

?>