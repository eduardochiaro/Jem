<?php
class Frontend {
	
	private $workspace;
	private $fields;
	
	
	function __construct($workspace, $standard=false, $task=false){
		
		$this->setWorkspace($workspace);
		$this->getTemplate();
	
	}
	
	function existModule($module){
		if(is_file(_FRONTEND_PATH."/modules/".$module.".php")){
			return true;
		}else{
			return false;
		}
	}
	function getWorkspace(){
		return $this->workspace;
	}
	
	function setWorkspace($workspace){
		$this->workspace=$workspace;
	}
	
	function getTemplate(){
		$path = _FRONTEND_PATH . "/index.php";
		if(is_file($path)){
			include($path);
		}
		
	}	
	function loadSlugs(){
		
		$filename = FileSystem::files(_COMPONENTS_PATH, "\.xml$", true, true);
		
		$append = array();
		
		foreach($filename as $file){
			$xml = simplexml_load_file($file);
			if($xml->parameters->slug != ""){
				$lug = str_replace("/","",$xml->parameters->slug);
				$append[$lug] = $xml->parameters->slugopen;
			}
		}
		return $append;
	}
	
	function getFromPermalink(){
		$workspace = JEM::getInstance();
		$module = false;
		
		$path = $workspace->getServer("REQUEST_URI");
		$path = str_replace(_SUB_PATH,"",$path);

		if($path){
			
			
			$slugs = Frontend::loadSlugs();
			$parts = explode("/", $path);
			
			// The second part (offset 1) is the part we look for
			if (count($parts) > 1) {
				$segment = $parts[0];
				
				foreach($slugs as $slug => $page){
					if($slug == $segment ){
						$module = (string)$page;
						$workspace->set("idelement", $parts[1]);
					}
				}
			}else{
				$module = $parts[0];	
			}
			
			$menu = new Frontmenu($workspace);
			$menu->getSingle("*","t.text_3 = '".$path."'");
			
			if($menu->id > 0){
				if(!$menu->isgroup){
					$module = "contents";
					$workspace->set("idelement", $menu->reference);
				}else{
					$module = $menu->getParameter("page");
				}
			}
		}

		if(!$module || !Frontend::existModule($module)){
			$module = "default";
		}
		return $module;
	}
	
	
	static function Header(){
		$workspace = JEM::getInstance();
		
		$dom = new DomDocument('1.0','iso-8859-15');
		
		$tit_le = $dom->appendChild($dom->createElement('title'));
		$tit_le->appendChild($dom->createTextNode(_SITE_NAME));
		
		$element = $dom->createElement( 'meta' );
        $element->setAttribute( 'http-equiv', "Content-Type" );
        $element->setAttribute( 'content', "text/html; charset=utf-8" );
        $dom->appendChild($element);
		
		$element = $dom->createElement( 'meta' );
        $element->setAttribute( 'name', "description" );
        $element->setAttribute( 'content', _SITE_NAME );
        $dom->appendChild($element);
		
		$element = $dom->createElement( 'meta' );
        $element->setAttribute( 'name', "keyword" );
        $element->setAttribute( 'content', _SITE_NAME );
        $dom->appendChild($element);
			
			
		echo $dom->saveHTML();
	}
	
	
	static function Module(){
		$workspace = JEM::getInstance();

		$module = Frontend::getFromPermalink();

		include(_FRONTEND_PATH."/modules/".$module.".php");
		
	}
	static function Path(){
		return _SUB_PATH."/frontend/";	
	}
	
	static function pathPhoto($media){
		return _SUB_PATH."/uploads/framework/".$media;	
	}

}