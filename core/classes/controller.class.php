<?php
class Controller{
	
	private $workspace;
	private $module;
	private $task;	
	
	
	function __construct($workspace,$module,$task){
		$this->setWorkspace($workspace);
		$this->setModule($module);
		$this->setTask($task);
		
	}

	function getWorkspace(){
		return $this->workspace;
	}
	
	function setWorkspace($workspace){
		$this->workspace=$workspace;
	}
	function getModule(){
		return $this->module;
	}
	
	function setModule($module){
		$this->module=$module;
	}
	
	function getTask(){
		return $this->task;
	}
	
	function setTask($task){
		$this->task=$task;
	}
	 
	function getParameters(){
		$trace = debug_backtrace();

		$filename = FileSystem::files(dirname($trace[0]['file']), "\.xml$", true, true);
		if($filename[0]){
			$xml = simplexml_load_file($filename[0]);
			if($xml->parameters){
				return $xml->parameters;
			}
		}
		
	}
	 
	function getNamed(){
		$trace = debug_backtrace();
		
		$filename = FileSystem::files(dirname($trace[0]['file']), "\.xml$", true, true);
		if($filename[0]){
			$xml = simplexml_load_file($filename[0]);
			if($xml->named){
				return $xml->named;
			}
		}
		
	}
}
?>