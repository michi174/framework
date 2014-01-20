<?php

namespace wsc\form;

use wsc\validator\ValidatorInterface;
use wsc\validator\ValidatorFactory;
/**
 *
 * @author Michi
 *        
 */
class Form 
{
	private $parameters	= array(
		'action'	=> NULL,
		'method'	=> "post"
	);
	
	private $fieldsets	= array();
	private $elements	= array();
	
	private $validators	= array();
	
	public function add($element)
	{
		
	}
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
	
	public function getForm()
	{
		
	}
	
}

?>