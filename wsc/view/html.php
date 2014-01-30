<?php

namespace wsc\view;

/**
 *
 * @author Michi
 *        
 */
class Html extends AbstractView 
{
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see \wsc\view\View_abstract::getContent()
	 *
	 */
	
	public function getView() 
	{
		return $this->getContent();
	}
	
}

?>