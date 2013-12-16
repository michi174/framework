<?php
namespace wsc\functions\tools;

class Tools
{
	public static function array_search_recursive($needle,$haystack)
	{
		foreach($haystack as $key=>$value)
		{
			$current_key=$key;
			if($needle===$value OR (is_array($value) && self::array_search_recursive($needle,$value) !== false)) {
				return $current_key;
			}
		}
		return false;
	}
}

?>