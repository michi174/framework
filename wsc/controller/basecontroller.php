<?php

namespace wsc\controller;

use wsc\application\Application;
use wsc\view\AbstractView;
use wsc\view\Html;
/**
 * Standardcontroller von dem alle Controller erben m�ssen.
 * 
 * @author Michi
 *        
 */
class BaseController 
{
	/**
	 * Beinhaltet ein View Objekt.
	 * 
	 * @var AbstractView
	 */
	protected $view = NULL;
	
	/**
	 * Erezugt ein View Objekt und gibt dieses zur�ck. Wird kein Parameter �bergeben,
	 * wird automatisch ein HTML View Objekt erzeugt.
	 * 
	 * @param AbstractView ViewObjekt	(Standardm��ig HTML)
	 * @return resource $view Das View Objekt
	 */
	protected function createView(AbstractView $view = null)
	{
		if($view instanceof AbstractView)
		{
			$this->view	= $view;
		}
		else
		{
			$this->view = new Html();
		}
		
		$view	= &$this->view;
		
		return $view;
	}
	
	
	/**
	 * Sendet die fertige View an die Response.
	 */
	public function sendView()
	{
		if($this->view instanceof AbstractView)
		{
			Application::getInstance()->load("response")->addContent($this->view->getView());
		}
	}
}

?>