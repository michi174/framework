<?php

namespace wsc\view\renderer;

/**
 *
 * @author Michi
 *        
 */
abstract class AbstractRenderer 
{	
	protected $options	= array(
			'fileextension'		=>	'php'
	);
	
	/**
	 * Gibt die gesuchte Option zur�ck.
	 * 
	 * @param string 	Die Option
	 * @return mixed | null
	 */
	public function __get($value)
	{
		if(array_key_exists($value, $this->options))
		{
			return $this->options[$value];
		}
	
		return null;
	}
	
	/**
	 * F�gt eine Option hinzu.
	 * 
	 * @param string $option	Name der Option
	 * @param mixed $value		Inhalt der Option
	 */
	protected function setOption($option, $value)
	{
		$this->options[$option]	= $value;
	}
	
	/**
	 * Rendert den �bergeben Inhalt.
	 * 
	 * @param string $content	Zu rendernder Inhalt
	 * @return string
	 */
	abstract public function render($content);
}

?>