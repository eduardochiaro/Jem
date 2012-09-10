<?php
class repliesHandler extends Handler{

	function __construct($workspace){
		parent::setWorkspace($workspace);	
	}
	function exec($parameters){
		$workspace	=	$this->getWorkspace();
		

		$model=new Reply($workspace);
		$model->getSingle("*",array("id"=>$parameters));	
		

		return $model;	
	}
}