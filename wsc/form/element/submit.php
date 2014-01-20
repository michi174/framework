<?php

namespace wsc\form\element;

/**
 *
 * @author Michi
 *        
 */
class Submit extends Element 
{
	public function __construct($name)
	{
		parent::__construct($name);
		$this->setAttribute("type", "submit");
	}
}

?>