<?php
class menuHandler extends Handler{

	function __construct($workspace){
		parent::setWorkspace($workspace);	
	}
	
	function exec(){
		$workspace	=	$this->getWorkspace();
		$my = $workspace->loadHandler("access");
		
		$menu = array();
		
		$required = FileSystem::files(_COMPONENTS_PATH, "\.xml$", true, true);
		if(is_array($required)){
			foreach ($required as $filename) {
				$xml = simplexml_load_file($filename);
				if($xml->voice){
					$ls = $xml->voice;
	
					$n = $this->_getNext((int)$ls["position"], $menu);
					
					$menu[$n] = $ls;
				}
			}
		}
		ksort($menu);
		
		if(!$my->root){
			$mypermits=unserialize($my->permits);
			
			$model=new Config($workspace);
			$model->getSingle("*",array("id"=>1));	
			$components = $model->renderComponents();
			
			$Nmenu = array();
			foreach($menu as $row){
				//$Nmenu = array_intersect($menu, $components);
				if((in_array($row['option'], $components) && in_array(strtoupper($row['option']), $mypermits) ) || $row['always']){
					$Nmenu[] = $row;
				}
			}
			$menu = $Nmenu;
		}
		
		return $menu;
	}
	
	private function _getNext($key, $menu){

		if(isset($menu[$key])){
			$n = $this->_getNext($key+1,$menu); 
		}else{
			$n = $key;	
		}
		return $n;
	}
}