<?php
class Mailing extends Model{
	var $id;
	var $type;
	var $title;
	var $sender;
	var $text;
	var $toptext;
	var $bottomtext;
	var $parameters;
	var $created;
	var $lastmodified;
	var $lastuser;
	var $sent;
	
	function __construct($workspace){
		parent::setWorkspace($workspace);
		parent::setTable("mailing");
		
		//array (validare,tipo,lunghezza,deve essere pieno)
		$this->setFields(array(
			'id'=>array(0,'int',8,false),
			'type'=>array(1,'int',8,true),
			'sender'=>array(1,'int',8,true),
			'title'=>array(1,'text',220,true),
			'text'=>array(1,'longtext','',true),
			'toptext'=>array(0,'longtext','',false),
			'bottomtext'=>array(0,'longtext','',false),
			'parameters'=>array(1,'longtext','',false),
			'created'=>array(1,'datetime','',true),
			'lastmodified'=>array(1,'datetime','',true),
			'lastuser'=>array(1,'int',8,true),
			'sent'=>array(1,'int',1,false)
		));
		$this->created = date("Y-m-d H:i:s");
		$this->sent = 0;
	}
	function save($post,$over=false){		
		if(!$post['lastmodified']){
			$post['lastmodified'] = date("Y-m-d H:i:s");
		}
     
		if(!$post['pubdate']){
			$post['pubdate'] = date("Y-m-d");
		}else{
			$post['pubdate'] = Utils::parseDate($post['pubdate'],"%Y-%m-%d");
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