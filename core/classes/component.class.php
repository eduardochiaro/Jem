<?php

class Component{
	
	function __construct($workspace,$html=true,$standard=false,$task=false){
		
		if($html){
			
			
			$module=$workspace->getRequest("m",$standard);
			$task=$workspace->getRequest("t",$task);
			
			$task=$this->getTask($task);
			$loading="exec".ucfirst($task);
			if($this->existModule($module)){
				$class=$module."Controller";

				if(is_callable(array($class,$loading))){
					$newclass = new $class($workspace,$module,$task);
					$newclass->$loading();
				}else{
					Utils::redirectOption($module);
				}
			}else{
				Log::error($module."/".$task." not found");
			}
		}
	
	}
	
	function existModule($module){
		if(is_file(_COMPONENTS_PATH."/".$module."/".$module.".controller.php")){
			return true;
		}else{
			return false;
		}
	}
	
	function getTask($task){
		if(!$task){
			$task="view";
		}
		return $task;
	}
}