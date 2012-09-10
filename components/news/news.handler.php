<?php
class newsHandler extends Handler{

	function __construct($workspace){
		parent::setWorkspace($workspace);	
	}
	
	function exec(){
		$workspace	=	$this->getWorkspace();
		
		
		return;
	}
	
	function getLatest(){
		$workspace	=	$this->getWorkspace();

		$model=new News($workspace);
		$list = $model->getAll("*",false,false,10);	
		
		return $list;
	}
	
	function getSpecific($parameter){
		$workspace	=	$this->getWorkspace();
		$module = "";
		$task = "";
		

		$model=new News($workspace);
		$model->getSingle("*",array("id"=>$parameter));	
		
		$new = new newsController($workspace,$module,$task);
		$parameters = $new->getParameters();
		
		$model->slug = $parameters->slug .$model->slug ;
		
		
		return $model;	
	}

}