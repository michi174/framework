<?php

namespace wsc\http_request;

/**
 *
 * @author Michi
 *        
 */
class Http_request {
	
	/**
	 */
	private $post;
	private $get;
	private $cookie;
	private $file;
	private $header;
	private $auth;
	private $MVC_Controller;
	private $MVC_Action;
	
	private static $instance = null;
	
	private function __construct()
	{
		$this->post =   &$_POST;
		$this->get  =   &$_GET;
		$this->cookie = &$_COOKIE;
		$this->file =   &$_FILES;
	
		foreach($_SERVER as $key => $value)
		{
			if(substr($key, 0, 5)== "HTTP_")
			{
				$key = strtolower($key);                 // weil es schöner aussieht
				$this->header[substr($key,5)] = $value;  //HTTP_ abschneiden
			}
		}
	
		if(isset($_SERVER["PHP_AUTH_USER"]))
		{
			$this->auth["user"] = $_SERVER["PHP_AUTH_USER"];
			$this->auth["pass"] = $_SERVER["PHP_AUTH_PW"];
		}
		else
		{
			$this->auth = null;
		}
	}
	
	private function __clone() {}
	
	public static function getInstance()
	{
		if(self::$instance === null)
		{
			self::$instance = new Http_Request();
		}
		return self::$instance;
	}
	
	public function setControllerName($name)
	{
		$this->MVC_Controller = $name;
	}
	
	public function getControllerName()
	{
		return $this->MVC_Controller;
	}
	
	public function setActionName($name)
	{
		$this->MVC_Action = $name;
	}
	
	public function getActionName()
	{
		return $this->MVC_Action;
	}
	
	public function getAuthData()
	{
		return $this->auth;
	}
	
	public function issetHeader($key)
	{
		$key = strtolower($key);
		return (isset($this->header[$key]));
	}
	
	public function getHeader($key)
	{
		$key = strtolower($key);
		if($this->issetHeader($key))
		{
			return $this->header[$key];
		}
		return null;
	}
	
	public function issetGet($key)
	{
		return (isset($this->get[$key]));
	}
	
	public function getGet($key)
	{
		if($this->issetGet($key))
		{
			return $this->get[$key];
		}
		return null;
	}
	
	public function issetPost($key)
	{
		return (isset($this->post[$key]));
	}
	
	public function getPost($key)
	{
		if($this->issetPost($key))
		{
			return $this->post[$key];
		}
		return null;
	}
	
	public function issetFile($key)
	{
		return (isset($this->file[$key]));
	}
	
	public function getFile($key)
	{
		if($this->issetFile($key))
		{
			return $this->file[$key];
		}
		return null;
	}
	
	public function issetCookie($key)
	{
		return (isset($this->cookie[$key]));
	}
	
	public function getCookie($key)
	{
		if($this->issetCookie($key))
		{
			return $this->cookie[$key];
		}
		return null;
	}
}

?>