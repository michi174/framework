<?php
namespace wsc\autoload;

use wsc\config\config;
/**
 *
 * @author Michi
 *        
 */
class Autoload
{
  /**
   * @access public
   * @param string classname
   * 
   * Lädt benötigte Klasse.
   *                
   */  
	public static function load($className)
	{
		$classfile = config::getInstance()->get("fw_path")."/".str_replace("\\", "/", $className).".php";
		
		//die($classfile);
		
		if(file_exists($classfile))
		{
			require_once($classfile);
		}
		else
		{
			//DEBUG
			/*echo "	<br /><br /> FW Autoloader Fehler: Die Datei <br /><strong>" . $classfile . " </strong><br /> 
					konnte nicht eingebunden werden, da die Datei nicht gefunden wurde.";*/
		}
	}
	
  /**
   * @access public static
   * @param mixed autoloader
   * 
   * registriert einen weiteren autoloader           
   */  
	private function __construct() {}
	private function __clone() {}
	public static function register($autoloader = null)
	{
		if($autoloader === null)
		{
			spl_autoload_register(array('wsc\autoload\Autoload', 'load'));
		}
		else
		{
			spl_autoload_register($autoloader);
		}
	}
}

?>