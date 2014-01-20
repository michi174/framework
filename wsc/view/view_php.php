<?php

namespace wsc\view;

use wsc\view\View_abstract;


/**
 *
 * @author Michi
 *        
 */
class View_php extends View_abstract 
{
	
	public function __construct($is_subcontroller = false)
	{
		parent::__construct($is_subcontroller);
		$this->setRenderer(array(
			'extension'	=> 'php',
			'name'		=> 'PHP'
		));
	}
	
	/**
	 *
	 * @see \wsc\view\View_abstract::render()
	 *
	 */
	public function render($content) 
	{
		return $content;
	}
}

?>