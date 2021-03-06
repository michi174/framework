<?php

namespace wsc\model;


use wsc\application\Application;
use wsc\database\Database;
/**
 *
 * @author Michi
 *        
 */
abstract class Model_abstract 
{
    const SORT_ASC  = "ASC";
    const SORT_DESC = "DESC";
    
    /**
     * 
     * @var Database
     */
    protected $database = null;
    
    public function __construct()
    {
        $this->database = Application::getInstance()->load("database");
    }
    
	protected function executeQuery($query)
	{
	    $res   = $this->database->query($query) or die(__FILE__.":".__LINE__. " meldet: ". $this->database->error);
	    
	    
	    if($res->num_rows > 0)
	    {
    	    while(($row = $res->fetch_assoc()) == true)
    	    {
    	        $ret[] = $row;
    	    }
    	    
    	    
    	    
    	    return $ret;
	    }
	    return null;
	}
}

?>