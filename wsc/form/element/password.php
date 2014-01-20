<?php

namespace wsc\form\element;

/**
 *
 * @author Michi
 *        
 */
class Password extends Element 
{
	public function __construct($name)
	{
		parent::__construct($name);
		$this->setAttribute("type", "password");
		$this->setAttribute("value", "");
	}
	
}

?>