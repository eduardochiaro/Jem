<?php
/**
 * @title			MENU
 * @description		modulo menu amministrativo
 * @task			
 * @required true
 */


class menuController extends Controller{
	
	var $_tags;
	
	function execMenu(){
		$workspace = $this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		
		$menu = $workspace->loadHandler("menu");
		
		
		Utils::getTemplate($workspace,$module,"menu",$menu,false);
	}
}