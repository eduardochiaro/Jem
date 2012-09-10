<?php
class GroupMailing extends Model{
	var $id;
	var $title;
	
	function __construct($workspace){
		parent::setWorkspace($workspace);
		parent::setTable("mailing_groups");
		
		//array (validare,tipo,lunghezza,deve essere pieno)
		$this->setFields(array(
			'id'=>array(0,'int',8,false),
			'title'=>array(1,'text',220,true)				
		));
	}
	
	function save($post,$over=false){				
		parent::bind($post,$over);
	}
}