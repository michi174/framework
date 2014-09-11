<?php

namespace wsc\view;
use wsc\application\Application;
use wsc\view\renderer\Php;
use wsc\view\renderer\AbstractRenderer;
/**
 *
 * @author Michi
 *        
 */
abstract class AbstractView 
{
	/**
	 * Dateipfad zum View Template
	 * @var string
	 */
	protected $file;
	
	/**
	 * Inhalt des View Templates
	 * @var string
	 */
	protected $content;
	
	/**
	 * Objekt des Renderers
	 * @var AbstractRenderer
	 */
	public $renderer;
	
	/**
	 * Im View Template verwendete Variablen
	 * @var array
	 */
	public $variables	= array();
	
	/**
	 * Registrierte ViewHelper
	 * @var array
	 */
	public $helpers  = array(
	    'form'         => 'wsc\form\view\helpers\form',
	    'formElement'  => 'wsc\form\view\helpers\formElement',
	    'formInput'    => 'wsc\form\view\helpers\formInput',
	    'formText'     => 'wsc\form\view\helpers\formText',
	    'formPassword' => 'wsc\form\view\helpers\formPassword',
	    'formHidden'   => 'wsc\form\view\helpers\formHidden',
	    'formSubmit'   => 'wsc\form\view\helpers\formSubmit',
	    'formButton'   => 'wsc\form\view\helpers\formButton',
	    'formReset'    => 'wsc\form\view\helpers\formReset',
	    'formRow'      => 'wsc\form\view\helpers\formRow',
	    'formLabel'    => 'wsc\form\view\helpers\formLabel',
	    'formSelect'   => 'wsc\form\view\helpers\formSelect',
	    'formCheckbox' => 'wsc\form\view\helpers\formCheckbox',
	    'formRadio'    => 'wsc\form\view\helpers\formRadio',
	);
	
	/**
	 * Legt den Standardrenderer fest.
	 */
	public function __construct()
	{
		$this->setRenderer(new Php());
	}
	
	/**
	 * Lädt den Inhalt der im View Template vorhandenen Variablen aus 
	 * $variables
	 * @param string $var	Die aufgerufene Variable.
	 */
	public function __get($var)
	{
		if(isset($this->variables[$var]))
		{
			return $this->variables[$var];
		}
	}
	
	/**
	 * Lädt die View Helfer, die im View Template benötigt werden.
	 * 
	 * @param string $method	Aufgerufener View Helfer
	 * @param array $params		Parameter für den View Helfer
	 */
	public function __call($method, $params)
	{
	    return $this->getHelper($method, $params);
	}
	
	/**
	 * Gibt die fertig gerenderte und eventuell konvertierte View zurück.
	 */
	abstract public function getView();
	
	/**
	 * Fügt weiteren Inhalt an.
	 *  
	 * @param string $content
	 */
	public function add($content)
	{
		$this->content	.= $content;
	}
	
	/**
	 * Definiert die Variablen die im View Template benötigt werden.
	 * Der erste Parameter muss dem Namen der zu verwendenden Variable im View Template entsprechen.
	 * 
	 * @param string $vars		Name der Variable im View Template.
	 * @param mixed $value		Inhalt durch die die Variable ersetzt wird.
	 */
	public function assign($vars, $value = "")
	{
		if(!is_array($vars))
		{
			$this->variables[$vars] = $value;
		}
	
		if(is_array($vars))
		{
			foreach ($vars as $var	=> $value)
			{
				$this->variables[$var]	= $value;
			}
		}
	}
	
	/**
	 * Ersetzt den Standardrenderer durch einen benutzerdefinierten Renderer.
	 * 
	 * @param AbstractRenderer $renderer	der Renderer
	 */
	public function setRenderer(AbstractRenderer $renderer)
	{
		$this->renderer	= $renderer;
	}
	
	/**
	 * Gibt einen manuellen Pfad zum View Template an.
	 * 
	 * @param string $file		Name der Datei inkl. Erweiterung
	 * @param string $path      Pfad zur Datei. Optional, wenn leer wird der Standardpfad des Controllers verwendet.
	 */
	public function setViewFile($file, $path = null)
	{
	    if(is_null($path))
	    {
	        $this->file    = $this->getViewDir()."/".$file;
	    }
	    else 
	    {
	        $this->file	= $file;
	    }
	}
	
	/**
	 * Gibt den Inhalt des View Templates zurück.
	 *
	 * @return string
	 */
	protected function getContent()
	{
		$this->openFile();
	
		return $this->content;
	}

	
	public function addHelper($name, $class)
	{
	    if(class_exists($class))
	    {
	        $this->helpers[$name]   = $class;
	    }
	}
	
	/**
	 * Gibt das Objekt eines ViewHelpers zurück.
	 * @param string $helper
	 */
	public function getHelper($helper, $params)
	{
        if(isset($this->helpers[$helper]))
        {
            return new $this->helpers[$helper]($params);
        }
        else 
        {
            $class  = "wsc\\view\\helper\\".$helper;
            if(class_exists($class))
            {
                return new $class($params);
            }
        }
	}
	/**
	 * Öffnet das View Template und rendert den Inhalt
	 */
	private function openFile()
	{
		if(empty($this->file))
		{
			$this->file  = $this->getTemplatePath();
		}
		if(file_exists($this->file))
		{
			ob_start();
			include $this->file;
			$content	= ob_get_contents();
			ob_end_clean();
			
			$content	= $this->render($content);
			$this->setContent($content);
		}
	}
	
	/**
	 * Übergibt den Content an den Renderer, der diesen rendert und zurückgibt.
	 *
	 * @param string $content
	 * @return string
	 */
	private function render($content)
	{
		if($this->renderer instanceof AbstractRenderer)
		{
			return $this->renderer->render($content);
		}
	
		return $content;
	}
	
	/**
	 * Setzt den Content.
	 * @param string $content	Gerenderter Inhalt des View Templates.
	 */
	private function setContent($content)
	{
		$this->content	.= $content;
	}
	
	/**
	 * Gibt den Pfad des Standardview Templates zurück.
	 * 
	 * @return string
	 */
	private function getTemplatePath()
	{
		return $this->getViewDir()."/".$this->getViewFileName().".".$this->renderer->fileextension;
	}
	
	/**
	 * Gibt den Pfad zum Standardview Template zurück.
	 * 
	 * @return string|boolean
	 */
	private function getViewDir()
	{
		$config	= Application::getInstance()->load("config");
		
		$doc_root	= $config->get("doc_root");
		$proj_path	= $config->get("project_dir");
		$view_path	= $config->get("view_dir");
		$controller = Application::getInstance()->load("FrontController")->getActiveController();
		
        $path	= $doc_root."/".$proj_path."/".$view_path."/".$controller;
			
		return $path;
	}
	
	/**
	 * Gibt den Namen des Standardview Templates zurück.
	 * @return string
	 */
	private function getViewFileName()
	{
		return Application::getInstance()->load("FrontController")->getActiveAction();
	}
}
?>