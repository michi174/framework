<?php
namespace wsc\http\response;
use wsc\application\Application;

/**
 *
 * Response (2013 - 12 - 27)
 * 
 * Die Klasse übernimmt das verarbeitete Request Objekt auf und kann dessen Daten noch manipulieren.
 * 
 * @author 		Michael Strasser
 * @name 		Response
 * @version		1.0
 * @copyright	2013 - Michael Strasser
 * @license		Alle Rechte vorbehalten.
 *        
 */
class Response 
{
	/**
	 * Das APP Objekt.
	 * 
	 * @var Application
	 */
	private $application;
	
	/**
	 * Array indem die zu verarbeitenden Header gespeichert sind.
	 * 
	 * @var array
	 */
	private $headers	= array();
	
	/**
	 * Der HTTP Status Code
	 * 
	 * @var int
	 */
	private $status		= 200; 		//Status is OK
	
	/**
	 * Der auszugebende Content.
	 * 
	 * @var string
	 */
	private $content	= null;
	

	public function __construct(Application $application)
	{
		$this->application	= $application;
	}
	
	/**
	 * Setzt den HTTP Status Code
	 * 
	 * @param int $code	HTTP Status Code
	 */
	private function setStatus($code)
	{
		$this->status	= (int)$code;
	}
	
	/**
	 * Gibt den HTTP Status Code zurück
	 * 
	 * @return int HTTP Status Code
	 */
	public function getStatus()
	{
		return $this->status;
	}
	
	/**
	 * Fügt einen Header zur Abarbeitung hinzu.
	 * 
	 * @param string $header
	 */
	public function setHeader($header)
	{
		$this->headers[]	= $header;
	}
	
	/**
	 * Fügt auszugebenden Inhalt an den bereits bestehenden Inhalt an.
	 * 
	 * @param string $content
	 */
	public function addContent($content)
	{
		$this->content	.=	$content;
	}
	
	/**
	 * Überschreibt den auzugebenden Inhalt mit neuem Inhalt.
	 * 
	 * @param string $content
	 */
	public function setContent($content)
	{
		$this->content	= $content;
	}
	
	/**
	 * Sendet das fertig verarbeitete HTTP Request an den Browser
	 */
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
	
	/**
	 * Setzt das Response Objekt auf den Ursprungszustand zurück.
	 */
	private function clear()
	{
		$this->content	= "";
		$this->headers	= array();
		$this->status	= (int)200;
	}
	
	/**
	 * Leitet den Benutzer sofort, ohne den HTTP Request zu verarbeiten, an eine 
	 * andere Seite weiter.
	 * 
	 * @param string $location
	 */
	public function redirect($location)
	{
		$this->clear();
		$this->setHeader("Location:".$location);
		$this->send();
	}
}

?>