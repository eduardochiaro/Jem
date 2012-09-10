<?php
class Access extends Model{
	var $id;
	var $username;
	var $password;
	var $salt;
	var $root=0;
	var $permits;
	
	function __construct($workspace){
		parent::setWorkspace($workspace);
		parent::setTable("access");
		
		//array (validare,tipo,lunghezza,deve essere pieno)
		$this->setFields(array(
			'id'=>array(0,'int',8,false),
			'username'=>array(1,'text',220,true),
			'password'=>array(1,'text',220,false),
			'salt'=>array(0,'text',220,false),
			'root'=>array(1,'int',1,true),
			'permits'=>array(1,'longtext','',false)
		));
		$this->salt=uniqid(true);
	}
	
	function save($post,$over=false){		
		if($post['password']){
			$post['password']=md5($post['password'].":".$this->salt);
		}
		if(is_array($post['permits'])){
			$post['permits']=serialize($post['permits']);
		}
		parent::bind($post,$over);
	}

	function isLogged(){
		return ($this->id)?true:false;
	}
	
	function getPermission($module){
		$permits=unserialize($this->permits);
		if(!is_array($permits)){
			$permits=array($permits);	
		}

		if(in_array((string)$module,$permits) || $this->root){
			return true;
		}else{
			return false;
		}
	}
}