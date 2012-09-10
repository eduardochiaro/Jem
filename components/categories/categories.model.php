<?php
class Category extends Model{
	var $id;
	var $title;
	var $model;
	var $publish;
	
	function __construct($workspace){
		parent::setWorkspace($workspace);
		parent::setTable("categories");
		
		//array (validare,tipo,lunghezza,deve essere pieno)
		$this->setFields(array(
			'id'=>array(0,'int',8,false),
			'model'=>array(1,'text','',false),
			'publish'=>array(1,'int',1,false)
		));
		$this->setTranslateFields(array(
			'title'=>array(1,'text',220,true)						
		));
		$this->publish = 0;
	}
	
	function save($post,$over=false){		

		parent::bind($post,$over);
	}
}