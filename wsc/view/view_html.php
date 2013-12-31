<?php

namespace wsc\view;

use wsc\view\View_abstract;


/**
 *
 * @author Michi
 *        
 */
class View_html extends View_abstract {
	
	private $content;
	
	public function add($content)
	{
		$this->content .= $content;
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \wsc\view\View_abstract::render()
	 *
	 */
	protected function render() 
	{
		return $this->content;
	}
}

?>