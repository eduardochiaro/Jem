<?php
class TemplateMailing extends Model{
	var $id;
	var $title;
	var $text;
	var $parameters;
	var $created;
	var $lastmodified;
	var $lastuser;
	
	function __construct($workspace){
		parent::setWorkspace($workspace);
		parent::setTable("mailing_templates");
		
		//array (validare,tipo,lunghezza,deve essere pieno)
		$this->setFields(array(
			'id'=>array(0,'int',8,false),
			'parameters'=>array(1,'longtext','',false),
			'created'=>array(1,'datetime','',true),
			'lastmodified'=>array(1,'datetime','',true),
			'lastuser'=>array(1,'int',8,true)						
		));
		$this->setTranslateFields(array(
			'title'=>array(1,'text',220,true),
			'text'=>array(1,'longtext','',true)
		));
		$this->created = date("Y-m-d H:i:s");
	}
	/*
	function loadTranslationSingle($row, $actual = true){
		$workspace=$this->getWorkspace();
		$translations = $workspace->getTranslation();
		
		
		$result =  $translations->getData($row, $actual);

		$result["text"] = str_replace('\"','',$result["text"]);

		Model::bindObject($row, $result, false);
		
		//$row->bind($result);
		//return $row; 
	}
	*/
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