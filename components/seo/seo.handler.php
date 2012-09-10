<?php
class seoHandler extends Handler{

	function __construct($workspace){
		parent::setWorkspace($workspace);	
	}
	
	function exec(){
		$workspace	=	$this->getWorkspace();
		
		$controller = new seoController($workspace,"seo",false);
		
		return $controller;
	}

}