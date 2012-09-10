<?php
class Media extends Model{
	var $id;
	var $title;
	var $media;
	var $format;
	var $category;
	var $created;
	var $lastmodified;
	var $lastuser;
	var $publish;
	
	function __construct($workspace){
		parent::setWorkspace($workspace);
		parent::setTable("media");
		
		//array (validare,tipo,lunghezza,deve essere pieno)
		$this->setFields(array(
			'id'=>array(0,'int',8,false),
			'media'=>array(1,'text',220,true),
			'format'=>array(1,'text',220,true),
			'category'=>array(1,'int',8,true),
			'created'=>array(1,'datetime','',true),
			'lastmodified'=>array(1,'datetime','',true),
			'lastuser'=>array(1,'int',8,true),
			'publish'=>array(1,'int',1,false)
		));
		$this->setTranslateFields(array(
			'title'=>array(1,'text',220,true),
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