<?php
class Contact extends Model{
	var $id;
	var $reply;
	var $parameters;
	var $created;
	var $status;
	
	function __construct($workspace){
		parent::setWorkspace($workspace);
		parent::setTable("contacts");
		
		//array (validare,tipo,lunghezza,deve essere pieno)
		$this->setFields(array(
			'id'=>array(0,'int',8,false),
			'reply'=>array(1,'int',8,true),
			'parameters'=>array(1,'longtext','',false),
			'created'=>array(1,'datetime','',true),
			'status'=>array(1,'int',1,false),
		));

		$this->created = date("Y-m-d H:i:s");
		$this->status = 0;
	}
	
	function save($post,$over=false){		
		if($post['parameters']){
			$post['parameters'] = serialize($post['parameters']);
		}
		parent::bind($post,$over);
	}
	
	function getParameters(){
		$workspace=$this->getWorkspace();
		
		$parameters=unserialize($this->parameters);
		if(!is_array($parameters)){
			return false;
		}
		
		return $parameters;

	}
	
	function getParameter($key, $default = false){
		$workspace=$this->getWorkspace();
		
		$parameters = $this->getParameters();
		
		if($parameters[$key]){
			return $parameters[$key];
		}else{
			return $default;
		}
	}
	function standard(){
		$standard = array(
						"name" => "nome",
						"surname" => "cognome",
						"email" => "email",
						"address" => "indirizzo",
						"city" => "citt&agrave;",
						"zip" => "cap",
						"province" => "province",
						"state" => "regione",
						"country" => "nazione",
						"phone" => "telefono",
						"phone2" => "telefono 2",
						"fax" => "fax",
						"fax2" => "fax 2",
						"mobile" => "cellulare",
						"message" => "messaggio",
						"ip" => "indirizzo i.p."
						  );	
		return $standard;
	}
	
	function getStardard($key){
		$standard = $this->standard();
		if($standard[$key]){
			return $standard[$key];
		}else{
			return false;
		}
	}
}