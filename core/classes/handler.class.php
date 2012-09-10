<?php
class Handler {
	
	private $workspace;

	function __construct($workspace){
		$this->setWorkspace($workspace);
	}

	function getWorkspace(){
		return $this->workspace;
	}
	
	function setWorkspace($workspace){
		$this->workspace=$workspace;
	}

}
?>