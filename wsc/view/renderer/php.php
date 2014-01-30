<?php

namespace wsc\view\renderer;


/**
 *
 * @author Michi
 *        
 */
class Php extends AbstractRenderer 
{	
	public function __construct()
	{
		$this->setOption("fileextension", "php");
	}
	
	/**
	 * Rendert den Inhalt und gibt ihn zurück. 
	 *
	 * @see \wsc\view\renderer\AbstractRenderer::render()
	 *
	 */
	public function render($content) 
	{
		return $content;
	}
	
}

?>