<?php
class GroupUserMailing extends Model{
	var $id;
	var $user;
	var $group;
	
	function __construct($workspace){
		parent::setWorkspace($workspace);
		parent::setTable("mailing_groups_users");
		
		//array (validare,tipo,lunghezza,deve essere pieno)
		$this->setFields(array(
			'id'=>array(0,'int',8,false),
			'user'=>array(1,'int',8,true),
			'group'=>array(1,'int',8,true)				
		));
	}
	
	function save($post,$over=false){				
		parent::bind($post,$over);
	}
}