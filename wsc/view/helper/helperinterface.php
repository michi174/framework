<?php
namespace wsc\view\helper;

/**
 *
 * @author Michi
 *        
 */
interface HelperInterface
{
    const TO_STRING_NOT_ALLOWED = "canNotBeConvertedToString";
    const NO_PARAMS             = "noParamsWereGiven";
    
    public function __construct($params = NULL);
    public function __toString();
}

?>