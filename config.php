<?php

use wsc\autoload\Autoload;

require_once 'wsc/autoload/autoload.php';
require_once 'wsc/config/config.php';

$config	= wsc\config\Config::getInstance();

$config->set("doc_root", $_SERVER['DOCUMENT_ROOT']);
//die($config->get("doc_root"));
$config->set("fw_path", $config->get("doc_root")."/framework");

Autoload::register();

?>