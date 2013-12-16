<?php

namespace wsc\http_response;

/**
 *
 * @author Michi
 *        
 */
class Http_response 
{

	private $headers = array();
	private $content = "";
	private $status  = "200 OK";

	private static $instance = null;

	private function __clone() {}
	public static function getInstance()
	{
		if(self::$instance === null)
		{
			self::$instance = new Http_Response();
		}
		return self::$instance;
	}

	public function addHeader($name, $content)
	{
		$this->headers[$name] = $content;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}

	public function addContent($content)
	{
		$this->content .= $content;
	}

	public function getContent()
	{
		return $this->content;
	}

	public function replaceContent($newContent)
	{
		$this->content = $newContent;
	}

	public function send()
	{
		header("HTTP/1.0 ".$this->status);
		foreach($this->headers as $name => $content)
		{
			header($name.": ".$content);
		}
		echo $this->content;

		//resetten
		$this->content = "";
		$this->headers = null;
	}
}

?>