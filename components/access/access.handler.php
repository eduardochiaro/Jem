<?php
class accessHandler extends Handler{

	function __construct($workspace){
		parent::setWorkspace($workspace);	
	}
	function exec(){
		$workspace	=	$this->getWorkspace();
		
		$login = $this->getAccess();
		
		//NOTE: aggiungere 404 per login utente non root su componenti privati
		
		$model=new Config($workspace);
		$model->getSingle("*",array("id"=>1));	
		$components = $model->renderComponents();
		
		if(!in_array($workspace->getRequest("m"), $components ) && !$login->root){
			Utils::redirectOption("index");
		}
		
		if($login == false){
			Utils::redirectOption("access","login");
		}else{
			return $login;	
		}
	}
	function auto(){
		$workspace	=	$this->getWorkspace();
		
		$id = $workspace->getSession("administrator",0);
		
		$model=new Access($workspace);
		$model->getSingle("*",array("id"=>$id));
		

		if($model->id > 0){
			$this->setAccess($model);
		}
	}
	
	/**
	 * Ritorna l'utente in sessione
	 */
	function getAccess() {
		$workspace	=	$this->getWorkspace();
		return $workspace->get(_JEM_USER_);
	}
	function setAccess($user) {
		$workspace	=	$this->getWorkspace();
		return $workspace->set(_JEM_USER_, $user);
	}
}