<?php
class Reply extends Model{
	var $id;
	var $email;
	var $title;
	var $text;
	var $created;
	var $lastmodified;
	var $lastuser;
	var $publish;
	
	function __construct($workspace){
		parent::setWorkspace($workspace);
		parent::setTable("replies");
		
		//array (validare,tipo,lunghezza,deve essere pieno)
		$this->setFields(array(
			'id'=>array(0,'int',8,false),
			'email'=>array(1,'longtext','',true),
			'created'=>array(1,'datetime','',true),
			'lastmodified'=>array(1,'datetime','',true),
			'lastuser'=>array(1,'int',8,true),
			'publish'=>array(1,'int',1,false)
		));
		$this->setTranslateFields(array(
			'title'=>array(1,'text',220,true),
			'text'=>array(0,'longtext','',false)							
		));
		$this->created = date("Y-m-d H:i:s");
		$this->publish = 0;
	}
	
	function save($post,$over=false){		
		if(!$post['lastmodified']){
			$post['lastmodified'] = date("Y-m-d H:i:s");
		}
		if(!$post['lastuser']){
			$workspace = $this->getWorkspace();
			$my = $workspace->loadHandler("access");
			$post['lastuser'] = $my->id;
		}
		parent::bind($post,$over);
	}

}