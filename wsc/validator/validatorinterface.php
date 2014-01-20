<?php

namespace wsc\validator;

/**
 *
 * @author Michi
 *        
 */
interface ValidatorInterface
{
	public function isValid($value);	
	public function getMessage();
}

?>