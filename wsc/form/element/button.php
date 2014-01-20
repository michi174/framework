<?php

namespace wsc\form\element;

/**
 *
 * @author Michi
 *        
 */
class Button extends Element 
{
	public function __construct()
	{
		$this->setAttribute("type", "button");
	}
}

?>