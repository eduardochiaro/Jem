<?php
class LogMailing extends Model{
	var $id;
	var $mailing;
	var $email;
	var $status;
	var $token;
	
	function __construct($workspace){
		parent::setWorkspace($workspace);
		parent::setTable("mailing_logs");
		
		//array (validare,tipo,lunghezza,deve essere pieno)
		$this->setFields(array(
			'id'=>array(0,'int',8,false),
			'mailing'=>array(1,'int',8,true),
			'email'=>array(1,'text',220,true),
			'status'=>array(1,'int',1,false),
			'token'=>array(1,'text',220,true)			
		));
		$this->status = 0;
		$this->token = Utils::generateHash();
	}
	
	function save($post,$over=false){		

		parent::bind($post,$over);
	}

}