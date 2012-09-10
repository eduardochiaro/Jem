<?php
class Config extends Model{
	var $id;
	var $sitename;
	var $timeformat;
	var $components;
	var $company;
	var $address;
	var $city;
	var $zip;
	var $state;
	var $vat;
	var $phone;
	var $phoneother;
	var $fax;
	var $faxother;
	var $mobile;
	var $replyto;
	var $analytics_username;
	var $analytics_password;

	
	
	
	function __construct($workspace){
		parent::setWorkspace($workspace);
		parent::setTable("config");
		
		//array (validare,tipo,lunghezza,deve essere pieno)
		$this->setFields(array(
			'id'=>array(0,'int',8,false),
			'sitename'=>array(1,'longtext','',true),
			'timeformat'=>array(1,'longtext','',false),
			'components'=>array(1,'longtext','',false),
			'company'=>array(0,'longtext','',false),
			'address'=>array(0,'longtext','',false),
			'city'=>array(0,'longtext','',false),
			'zip'=>array(0,'longtext','',false),
			'state'=>array(0,'longtext','',false),
			'vat'=>array(0,'longtext','',false),
			'phone'=>array(0,'longtext','',false),
			'phoneother'=>array(0,'longtext','',false),
			'fax'=>array(0,'longtext','',false),
			'faxother'=>array(0,'longtext','',false),
			'mobile'=>array(0,'longtext','',false),
			'replyto'=>array(0,'longtext','',false),

		));
	}
	
	function save($post,$over=false){		
		if(is_array($post['components'])){
			$post['components']=serialize($post['components']);
		}
		if(is_array($post['informations'])){
			$post['informations']=serialize($post['informations']);
		}
		if(is_array($post['utilities'])){
			$post['utilities']=serialize($post['utilities']);
		}
		parent::bind($post,$over);
	}
	
	function isLogged(){
		return ($this->id)?true:false;
	}
	
	function getComponents(){
		$workspace=$this->getWorkspace();
		
		$permits=unserialize($this->components);
		if(!is_array($permits)){
			$permits = array();
		}
		return $permits;
	}
	
	function renderComponents(){
		$workspace=$this->getWorkspace();
		
		
		$components = $this->_arrayToLower(array_keys($this->getComponents()));
		
		return $components;
	}
	
	function addToComponents($com){
		$workspace=$this->getWorkspace();
		
		$permits=unserialize($this->components);
		$permits[$com] = true;
		
		$this->components = serialize($permits);
		return true;
	}
	
	function removeToComponents($com){
		$workspace=$this->getWorkspace();
		
		$permits=unserialize($this->components);
		unset($permits[$com]);
		
		$this->components = serialize($permits);
		return true;
	}
	private function _arrayToLower($array, $include_leys=false) {
	
		if($include_leys) {
			foreach($array as $key => $value) {
				if(is_array($value))
					$array2[strtolower($key)] = arraytolower($value, $include_leys);
				else
					$array2[strtolower($key)] = strtolower($value);
			}
			$array = $array2;
		}else {
			foreach($array as $key => $value) {
				if(is_array($value))
					$array[$key] = arraytolower($value, $include_leys);
				else
					$array[$key] = strtolower($value);  
			}
		}
		
		return $array;
	} 
}