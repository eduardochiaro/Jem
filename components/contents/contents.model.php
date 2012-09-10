<?php
class Content extends Model{
	var $id;
	var $title;
	var $slug;
	var $pretext;
	var $text;
	var $parameters;
	var $created;
	var $lastmodified;
	var $lastuser;
	var $publish;
	
	function __construct($workspace){
		parent::setWorkspace($workspace);
		parent::setTable("contents");
		
		//array (validare,tipo,lunghezza,deve essere pieno)
		$this->setFields(array(
			'id'=>array(0,'int',8,false),
			'parameters'=>array(1,'longtext','',false),
			'created'=>array(1,'datetime','',true),
			'lastmodified'=>array(1,'datetime','',true),
			'lastuser'=>array(1,'int',8,true),
			'publish'=>array(1,'int',1,false)
		));
		$this->setTranslateFields(array(
			'title'=>array(1,'text',220,true),
			'slug'=>array(1,'text',220,true),
			'pretext'=>array(0,'longtext','',false),
			'text'=>array(0,'longtext','',false)							
		));
		$this->created = date("Y-m-d H:i:s");
		$this->publish = 0;
	}
	
	function save($post,$over=false){		
		if(!$post['slug']){
			$post['slug'] = Formatting::sanitizeTitleWithDashes($post['title']);	
		}	
		if(!$post['lastmodified']){
			$post['lastmodified'] = date("Y-m-d H:i:s");
		}
		if(!$post['lastuser']){
			$workspace = $this->getWorkspace();
			$my = $workspace->loadHandler("access");
			$post['lastuser'] = $my->id;
		}
		if($post['parameters']){
			$post['parameters'] = serialize($post['parameters']);
		}
		parent::bind($post,$over);
	}

	function isLogged(){
		return ($this->id)?true:false;
	}
	
	function getParameter($key){
		$workspace=$this->getWorkspace();
		
		$parameters=unserialize($this->parameters);
		if(!is_array($parameters)){
			return false;
		}
		
		if($parameters[$key]){
			return $parameters[$key];
		}else{
			return false;
		}
	}
}