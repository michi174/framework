<?php

namespace wsc\form;

use wsc\validator\ValidatorInterface;
use wsc\validator\ValidatorFactory;
use wsc\form\element\Element;
/**
 *
 * @author Michi
 *        
 */
class Form 
{
	private $attributes	= array(
		'action'	=> NULL,
		'method'	=> "post"
	);
	
	private $fieldsets	= array();
	private $elements	= array();
	private $validators	= array();
	
	/**
	 * Fügt der Form ein Element hinzu.
	 * 
	 * @param Element $element
	 */
	public function add(Element $element)
	{
		
	}
	
	/**
	 * Gibt die Fertige Form zurück
	 * @return $form 	Die fertige Form
	 */
	
	public function getForm()
	{
		
		return;
	}
	
	/**
	 * Fügt der gesamten Form einen oder mehrere Validatoren hinzu.
	 * 
	 * @param unknown $validators
	 */
	public function addValidators($validators)
	{
		if(!is_array($validators))
		{
			$validators	= array($validators);
		}
		if(is_array($validators))
		{
			foreach ($validators as $validator)
			{
				if($validator instanceof ValidatorInterface)
				{
					$this->validators[]	= $validator;
				}
				else 
				{
					$factory	= new ValidatorFactory();
					
					try 
					{
						$validator	= $factory->getValidator($validator);
						$this->validators[] = $validator;
					} 
					catch (\Exception $e) 
					{
						echo nl2br("Exception: ".$e->getMessage()."\n".$e->getTraceAsString());
					}
				}
			}
		}
	}
	
}

?>