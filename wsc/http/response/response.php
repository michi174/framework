<?php

namespace wsc\http\response;

use wsc\application\Application;
/**
 *
 * @author Michi
 *        
 */
class response 
{
	private $application;
	
	private $headers	= array();
	private $status		= 200; //Status is OK
	private $content	= null;
	

	public function __construct(Application $application)
	{
		$this->application	= $application;
	}
	
	private function setStatus($code)
	{
		$this->status	= (int)$code;
	}
	public function getStatus()
	{
		return $this->status;
	}
	public function setHeader($header)
	{
		$this->headers[]	= $header;
	}
	
	public function addContent($content)
	{
		$this->content	.=	$content;
	}
	
	public function setContent($content)
	{
		$this->content	= $content;
	}
	
	public function send()
	{
		if(!empty($this->headers))
		{
			foreach($this->headers as $header)
			{
				header($header);
			}
		}
		
		echo $this->content;
		
		$this->clear();
	}
	
	private function clear()
	{
		$this->content	= "";
		$this->headers	= array();
		$this->status	= (int)200;
	}
	
	public function redirect($location)
	{
		$this->clear();
		$this->setHeader("Location:".$location);
		$this->send();
	}
}

?>