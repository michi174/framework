<?php
namespace wsc\form\element;


use wsc\form\view\helpers\formTextarea;
/**
 *
 * @author michi_000
 *        
 */
class Textarea extends Element
{
    public function __construct($name)
    {
        parent::__construct($name);
    }
    
    public function getDefaultViewHelper()
    {
        return new formTextarea();
    }
}

?>