<?php
class Seo extends Model{
	var $id;
	var $model;
	var $reference;
	var $title;
	var $description;
	var $metatag;
	
	function __construct($workspace){
		parent::setWorkspace($workspace);
		parent::setTable("seo");
		
		//array (validare,tipo,lunghezza,deve essere pieno)
		$this->setFields(array(
			'model'=>array(1,'text',220,true),
			'reference'=>array(1,'int',20,true)
			));
		$this->setTranslateFields(array(
			'title'=>array(1,'text',220,false),
			'description'=>array(1,'text',220,false),
			'metatag'=>array(0,'text',220,false)							
		));
	}
	
	function save($post,$over=false){		
		parent::bind($post,$over);
	}
}