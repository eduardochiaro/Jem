<?php
class contentsHandler extends Handler{

	function __construct($workspace){
		parent::setWorkspace($workspace);	
	}
	
	function exec(){
		$workspace	=	$this->getWorkspace();
		
		
		return;
	}
	function getForMenu($parameter){
		$workspace	=	$this->getWorkspace();
		$module = "";
		$task = "";

		$model = new Content($workspace);
		$all = $model->getAll("*");
		$menu = array();
		foreach($all as $row){
			$menu[$row->id] = $row->title;
		}

		return $menu;
	}
	function getPermalink($parameter){
		$workspace	=	$this->getWorkspace();
		$module = "";
		$task = "";
		
		$model = $this->getSpecific($parameter);
		
		return $model->slug;
	}
	function getSpecific($parameter){
		$workspace	=	$this->getWorkspace();
		$module = "";
		$task = "";
		
		

		$model=new Content($workspace);
		$model->getSingle("*",array("id"=>$parameter));	
		
		$new = new contentsController($workspace,$module,$task);
		$parameters = $new->getParameters();
		
		$model->slug = $parameters->slug .$model->slug ;
		

		return $model;	
	}
}