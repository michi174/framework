<?php
namespace wsc\form\element;

class Hidden extends Element
{
    public function __construct($name)
    {
        parent::__construct($name);
        $this->setAttribute("type", "hidden");
    }
}

?>