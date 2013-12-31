<?php
namespace wsc\functions\tools;

use wsc\application\Application;
class Tools
{
	/**
	 * Durchsucht mehrdimensionale Arrays auf das Vorkommen eines Arraykeys.
	 * Gibt false zurück wenn nichts gefunden wurde.
	 * 
	 * @param mixed $needle
	 * @param array $haystack
	 * @return mixed arraykey | boolean
	 */
	public static function array_search_recursive($needle, $haystack)
	{
		foreach($haystack as $key => $value)
		{
			$current_key	= $key;
			
			if($needle === $value || (is_array($value) && self::array_search_recursive($needle,$value) !== false))
			{
				return $current_key;
			}
		}
		return false;
	}
	
	public static function internalRedirect($controller, $action)
	{
		$app	= Application::getInstance();
		$cfg	= $app->load("config");
		
		$app->load("Response")->redirect("?".$cfg->get("ControllerName")."=". $controller ."&".$cfg->get("ActionName")."=".$action);
	}
}

?>