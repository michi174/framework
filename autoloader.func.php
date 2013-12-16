<?php
function __autoload($klasse)
{
	$include	= DOCUMENT_ROOT."/lib/".str_replace("\\", "/", $klasse).".php";
	
	if(file_exists($include))
	{
		include_once $include;
	}
	else 
	{
		die("<br /><br />Fehler: Die Datei <br /><strong>" . $include . " </strong><br /> konnte nicht eingebunden werden, da die Datei nicht gefunden wurde.");
	}
}

?>