<?php

namespace wsc\template;

/**
 *
 * @author Michi
 *        
 */
class NewTemplate
{
	private $delimeter		= array(
		'left'	=> '{',
		'right'	=> '}'
	);
	private $template_dir	= NULL;
	private $template		= NULL;
	private $assigned_vars	= array();
	private $tokens			= array();
	private $token_level	= 0;
	private $token_id		= 0;
	private $uncompiled		= NULL;
	private $precompiled	= NULL;
	private $output			= NULL;
	private $errors			= array();
	
	
	public function __construct(){

	}
	
	public function setTemplateDir($dir){
		
		if(!empty($dir))
		{
			if(substr($dir, -1, 1) == "/")
			{
				$this->template_dir	= $dir;
			}
			else
			{
				$this->template_dir = $dir . "/";
			}
			return true;
		}
		else
		{
			$this->addError("Templateverzeichnis wurde nicht angegeben!");
		}
	}
	public function setTemplate($template){
		if(!empty($template))
		{
			$this->template	= $template;
			return true;
		}
		else
		{
			$this->addError("Template wurde nicht angegeben!");
		}
	}
	public function assign($var, $value){
		if(!empty($var))
		{
			$this->tpl_vars[$var]	= $value;
		}
	}
	public function addFunction($var, $function){
		
	}
	public function render(){
		$this->readTemplate();
		$this->uncompiled	= $this->parseTags($this->uncompiled);
		$this->compile();
	
		return $this->uncompiled;
	}
	public function getErrors(){
		return $this->errors;
	}
	private function readTemplate(){
		$file	= $this->template_dir.$this->template;
		
		if(file_exists($file))
		{
			$this->uncompiled	= file_get_contents($file);
			return true;
		}
		else
		{
			$this->addError("Das Template &rsquo;" . $file . "&rsquo; wurde nicht gefunden!");
		}
	}
	private function addError($error){
		$this->errors[]	= $error;
	}
	private function parseTags(&$section, $parent = NULL)
	{
		
		$this->token_level += 1;
		
		$pattern_if_open		= '#\{if[\s]{1}.+[\s]{1}}\{\/if\}#ismU'; 								//{if condition }bei if ausführen{else (optional)}bei else ausführen{/if} 	| ws=whitespace
		$pattern_if_close		= '\{\/if\}';
		$pattern_foreach_open	= '#\{foreach[\s]{1}.+[\s]{1}as[\s]{1}.+([\s]{1}=>[\s]{1}.+)?\}\{\/foreach\}#ismU';	//{foreach NAMES as KEY => NAME}to loop{/foreach}
		$pattern_foreach_close	= '\{\/foreach\}';
		
		$matches_open_ifs		= array();
		$matches_open_foreach	= array();
		
		while(preg_match($pattern_if_open, $section, $matches_open_ifs))
		{
			$open_ifs[]	= $matches_open_ifs[0];
		}
		while(preg_match($pattern_foreach_open, $section, $matches_open_foreach))
		{
			$open_foreach[]	= $matches_open_foreach[0];
		}
		
		$pattern_if			= '(.*(\{else\}.*)?)';
		$pattern_foreach	= '(.*)';
		
		$matches	= NULL;
		
		while(preg_match($pattern_if, $section, $matches) || preg_match($pattern_foreach, $section, $matches))
		{
			
			$this->token_id += 1;
			
			$token			= $matches[0];
			$token_inhalt	= $matches[1];
			$token_id		= "internalToken_".$this->token_level."_".$this->token_id."";
			
			$this->tokens[$this->token_level][$token_id]	= $token;		
			
			if(preg_match($pattern_if, $token_inhalt, $matches) || preg_match($pattern_foreach, $token_inhalt, $matches))
			{
				$this->tokens[$this->token_level][$token_id]	= str_replace($token_inhalt, $this->parseTags($token_inhalt, $parent = $token_id), $this->tokens[$this->token_level][$token_id]);
			}
			$section	= str_replace($token, "{".$token_id."}", $section);
		}
		$this->token_level -= 1;
		
		return $section;
		
	}
	private function compile(){
		foreach ($this->tokens as $level => $tokens)
		{
			foreach ($tokens as $token_id => $token_value)
			{
				echo $token_value."<br><br>";
			}
		}
	}
	private function compileIf($section){
		
	}
	private function compileForeach($section){
		
	}
	private function compileArrayKey($section){
		
	}
	private function compileVar($section){
		
	}
	
}

?>