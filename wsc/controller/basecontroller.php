<?php

namespace wsc\controller;

use wsc\view\View_abstract;
/**
 *
 * @author Michi
 *        
 */
class BaseController 
{
	protected $view = NULL;
	
	
	public function __call($method, $params)
	{
		die( "ViewHelper wurde im Controller nicht gefuden! (Sind noch nicht verfuegbar)");
	}
	
	public function __get($var)
	{
		if(isset($this->view->variables[$var]))
		{
			return $this->view->variables[$var];
		}
	}
	
	public function sendView(View_abstract $view = NULL)
	{
		if($view instanceof View_abstract)
		{
			$this->view	= $view;
		}
	
		if($this->view instanceof View_abstract)
		{
			//View Template includieren
			ob_start();
			include $this->view->getTemplatePath();
			$content	= ob_get_contents();
			ob_end_clean();
				
			//Dateiinhalt an den Renderer weitergeben.
			$content	= $this->view->render($content);
				
			//Gerenderten Inhalt zur Ausgabe hinzufügen.
			$this->view->add($content);
		}
	}
}

?>