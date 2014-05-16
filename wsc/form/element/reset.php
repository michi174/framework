<?php

namespace wsc\form\element;

/**
 *
 * @author Michi
 *        
 */
class Reset extends Element 
{
	public function __construct($name)
	{
	    parent::__construct($name);
		$this->setAttribute("type", "reset");
	}
}

?>