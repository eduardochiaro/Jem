<?php
class CV extends Model{
	var $id;
	var $job;
	var $parameters;
	var $created;
	var $status;
	
	function __construct($workspace){
		parent::setWorkspace($workspace);
		parent::setTable("cv");
		
		//array (validare,tipo,lunghezza,deve essere pieno)
		$this->setFields(array(
			'id'=>array(0,'int',8,false),
			'job'=>array(1,'int',8,true),
			'parameters'=>array(1,'longtext','',false),
			'created'=>array(1,'datetime','',true),
			'status'=>array(1,'int',1,false),
		));
		$this->setTranslateFields(array(							
		));
		$this->status = 1;
		$this->created = date("Y-m-d H:i:s");
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
	
	function getParameter($key){
		$workspace=$this->getWorkspace();
		
		$parameters = $this->getParameters();
		
		if($parameters[$key]){
			return $parameters[$key];
		}else{
			return false;
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
						"nation" => "nazione",
						"phone" => "telefono",
						"phone2" => "telefono 2",
						"region" => "regione",
						"fax" => "fax",
						"fax2" => "fax 2",
						"mobile" => "cellulare",
						"message" => "curriculum testuale",
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