<?php
class jobsHandler extends Handler{

	function __construct($workspace){
		parent::setWorkspace($workspace);	
	}
	function exec($parameters){
		$workspace	=	$this->getWorkspace();
		

		$model=new Job($workspace);
		$model->getSingle("*",array("id"=>$parameters));	
		

		return $model;	
	}
}