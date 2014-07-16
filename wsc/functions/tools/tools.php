<?php
namespace wsc\functions\tools;

use wsc\application\Application;
use wsc\form\FormInterface;
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
	
	public static function internalRedirect($controller, $action = null, $params = array())
	{
		$app	= Application::getInstance();
		$cfg	= $app->load("config");
		
		$query_string	= "";
		
		if(!empty($params))
		{
			foreach ($params as $key => $value)
			{
				$query_string	.= "&".$key."=".$value;
			}
		}
		
		$app->load("Response")->redirect("?".$cfg->get("ControllerName")."=". $controller ."&".$cfg->get("ActionName")."=".$action.$query_string);
	}
	
	public static function getCompiledFileContent($file)
	{
		if(file_exists($file))
		{
			ob_start();
				
			include $file;
			$content	= ob_get_clean();
				
			ob_end_clean();
			
			return $content;
		}
		
		return false;
		
	}
	
	public static function getFormattedFormErrors(FormInterface $form)
	{
	    $message  = "Es sind folgende Fehler bei der Validierung aufgetreten:<br><dl>";
	    
	    foreach ($form->getMessages() as $element => $messages)
	    {
	        $element  = $form->get($element)->getDisplayName();
	        $message  .= "<dt>".$element."</dt>";
	    
	        foreach ($messages as $element_msg)
	        {
	            $message  .= "<dd>&bull;&nbsp;" . $element_msg . "</dd>";
	        }
	    }
	    
	    $message  .= "</dl>";
	    
	    return $message;
	}
}

?>