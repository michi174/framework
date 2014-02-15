<?php

namespace wsc\http\request;
use wsc\application\Application;

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
	private $post      = array();
	private $get       = array();
	private $file      = array();
	private $cookie    = array();
	private $request   = array();
	
	private $actionname        = NULL;
	private $controllername    = NULL;
	private $modulname         = NULL;
	
	private $controller    = "index";
	private $action        = "default";
	private $modul         = NULL;
	
	private $application;
	
	
	private $config	= NULL;
	
	public function __construct(Application &$application)
	{
		if($application instanceof Application)
		{
			$this->application	= $application;
		}
		
		$this->cookie		= &$_COOKIE;
		$this->post			= &$_POST;
		$this->get			= &$_GET;
		$this->file			= &$_FILES;
		
		$this->request		= array_merge($this->post, $this->get);
		
		$this->config		= $this->application->load("Config");
		
		try 
		{
		    $this->setModulName();
			$this->setControllerName();
			$this->setActionName();			
		}
		catch (\Exception $e)
		{
			echo $e->getMessage() . " in Datei " . $e->getFile() . " : " . $e->getLine() . " <br />";
			echo nl2br($e->getTraceAsString());
		}

		$this->setController();
		$this->setAction();
		$this->setModul();
	}
	
	/**
	 * Filtert den Controller aus dem Request heraus.
	 */
	private function setController()
	{
		$this->controller	= (isset($this->request[$this->getControllerName()])) ? $this->request[$this->getControllerName()] : "";
		unset($this->request[$this->getControllerName()]);
		
	}
	/**
	 * Filtert die Action aus dem Request heraus.
	 */
	private function setAction()
	{
		$this->action		= (isset($this->request[$this->getActionName()])) ? $this->request[$this->getActionName()] : "";
		unset($this->request[$this->getActionName()]);
	}
	
	/**
	 * Filter das Modul aus dem Request heraus.
	 */
	private function setModul()
	{
	    $this->modul		= (isset($this->request[$this->getModulName()])) ? $this->request[$this->getModulName()] : "";
	    unset($this->request[$this->getModulName()]);
	}
	
	/**
	 * Gibt den aktuell verwendeten Controller zurück.
	 * 
	 * @return string	Der aktuelle Controller
	 */
	public function getController()
	{
		return $this->controller;
	}
	
	/**
	 * Gib die aktuell verwendete Action zurück.
	 * 
	 * @return (string) 	Die aktuelle Action
	 */
	public function getAction()
	{
		return $this->action;
	}
	
	public function getModule()
	{
	    return $this->modul;
	}
	
	/**
	 * Gibt ein $_GET Arrayelement aus.
	 * 
	 * @param mixed $var
	 * @return multitype: mixed|NULL
	 */
	public function get($var = null)
	{
	    if(!is_null($var))
	    {
	        if($this->issetGet($var))
	        {
	            return $this->get[$var];
	        }
	        else 
	        {
	            return null;
	        }
	    }
        else 
        {
            return $this->get;
        }
	}
	
	
	/**
	 * Gibt ein $_POST Arrayelement zurück.
	 *
	 * @param mixed $var	Das gewünschte POST Element.
	 * @return multitype:mixed|NULL
	 */
	public function post($var = null)
	{
	    if(!is_null($var))
	    {
	        if($this->issetPost($var))
	        {
	            return $this->post[$var];
	        }
	        else
	        {
	            return null;
	        }
	    }
		else
		{
		    return $this->post;
		}
	}
	
	/**
	 * Gibt ein $_POST oder $_GET Arrayelement zurück.
	 *
	 * @param mixed $var	Das gewünschte REQUEST Element.
	 * @return multitype:mixed|NULL
	 */
	public function request($var)
	{
		if($this->issetPost($var))
		{
			return $this->post[$var];
		}
		elseif($this->issetGet($var))
		{
			return $this->get[$var];
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
	public function getModulName()
	{
	    return $this->modulname;
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
	
	/**
	 * Ließt den Modulnamen aus der Config aus und legt diesen fest.
	 * @throws \Exception
	 */
	private function setModulName()
	{
	    if(!$this->config->get("modulname") || $this->config->get("modulname") == "")
	    {
            throw new \Exception("Es muss ein Actionname in der Konfiguration festegelegt werden (Bsp.: config->set(modulname, 'modul')");
	    }
	    
	    $this->modulname = $this->config->get("modulname");
	}
}

?>