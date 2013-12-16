<?php

namespace wsc\http\Request;
use wsc\config\Config;

/**
 *
 * Request (2013 - 12 - 16)
 * 
 * Klasse um HTTP Request zu verarbeiten.
 * 
 * @author 		Michael Strasser
 * @name 		Request
 * @version		1.0
 * @copyright	2013 - Michael Strasser
 * @license		Alle Rechte vorbehalten.
 *        
 */
class Request 
{
	private $post		= array();
	private $get		= array();
	private $file		= array();
	private $cookie		= array();
	private $request	= array();
	
	private $actionname		= NULL;
	private $controllername	= NULL;
	
	private $controller	= "start";
	private $action		= "default";
	
	
	private $config	= NULL;
	
	public function __construct()
	{
		$this->cookie	= &$_COOKIE;
		$this->post		= &$_POST;
		$this->get		= &$_GET;
		$this->file		= &$_FILES;
		
		$this->request	= array_merge($this->post, $this->get);
		
		$this->config	= Config::getInstance();
		
		try 
		{
			$this->setControllerName();
			$this->setActionName();
			
			$this->controller	= $this->request[$this->getControllerName()];
			$this->action		= $this->request[$this->getActionName()];
			
		}
		catch (\Exception $e)
		{
			echo $e->getMessage() . " in Datei " . $e->getFile() . " : " . $e->getLine() . " <br />";
			echo $e->getTraceAsString();
		}		
	}
	
	public function getController()
	{
		return $this->controller;
	}
	
	public function getAction()
	{
		return $this->action;
	}
	
	/**
	 * Gibt ein $_GET Arrayelement aus.
	 * 
	 * @param mixed $var
	 * @return multitype:mixed|NULL
	 */
	public function get($var)
	{
		if($this->issetGet($var))
		{
			return $this->get[$var];
		}
		
		return null;
	}
	
	
	/**
	 * Gibt ein $_POST Arrayelement aus.
	 *
	 * @param mixed $var
	 * @return multitype:mixed|NULL
	 */
	public function post($var)
	{
		if($this->issetPost($var))
		{
			return $this->post[$var];
		}
		
		return null;
	}
	
	
	/**
	 * Gibt ein $_FILE Arrayelement aus.
	 *
	 * @param mixed $var
	 * @return multitype:mixed|NULL
	 */
	public function file($var)
	{
		if($this->issetFile($var))
		{
			return $this->file[$var];
		}
		
		return null;
	}
	
	
	/**
	 * Gibt ein $_COOKIE Arrayelement aus.
	 *
	 * @param mixed $var
	 * @return multitype:mixed|NULL
	 */
	public function cookie($var)
	{
		if($this->issetCookie($var))
		{
			return $this->cookie[$var];
		}
		
		return null;
	}
	
	public function issetFile($var)
	{
		return isset($this->file[$var]);
	}
	
	public function issetGet($var)
	{
		return isset($this->get[$var]);
	}
	
	public function issetPost($var)
	{
		return isset($this->post[$var]);
	}
	
	public function issetCookie($var)
	{
		return isset($this->cookie[$var]);
	}
	
	public function getActionName(){
		return $this->actionname;
	}
	public function getControllerName(){
		return $this->controllername;
	}
	
	
	/**
	 * setController
	 * 
	 * Ließt den Controller aus der Config und legt ihn fest.
	 * 
	 * @throws \Exception
	 */
	private function setControllerName()
	{
		if(!$this->config->get("controllername") || $this->config->get("controllername") == "")
		{
			throw new \Exception("Es muss ein Controllername in der Konfiguration festegelegt werden (Bsp.: config->set(controllername, 'controller')");
		}
		
		$this->controllername = $this->config->get("controllername");
	}
	
	
	/**
	 * setAction
	 *
	 * Ließt die Action aus der Config und legt ihn fest.
	 *
	 * @throws \Exception
	 */
	private function setActionName()
	{
		if(!$this->config->get("actionname") || $this->config->get("actionname") == "")
		{
			throw new \Exception("Es muss ein Actionname in der Konfiguration festegelegt werden (Bsp.: config->set(actionname, 'action')");
		}
		
		$this->actionname = $this->config->get("actionname");
	}
}

?>