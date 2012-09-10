<?php
class Utils{
	static function redirect($redirect){
		header("location: ".$redirect);
		exit();
	}
	static function redirectOption($module=false,$task=false,$others=false){
		header("location: ".Utils::makeLink($module,$task,$others));
		exit();
	}
	static function siteOptions($options){
		switch($options){
			case "sitename":
				return _SITE_NAME;
				break;
			case "siteurl":
				return _SITE_PATH;
				break;
			case "backend":
				return _SITE_PATH."administrator/";
				break;
			case "frontend":
				return _SITE_PATH;
				break;
			case "version":
				return _VERSION_NUMBER;
				break;
			case "codename":
				return _VERSION_NAME;
				break;
		}
	}
	
	static function getTitle($workspace,$original,$glue=" | "){
		if($workspace->getSession("preappend")) $pre	=	implode($glue,$workspace->getSession("preappend")).$glue;
		if($workspace->getSession("postappend")) $post	=	$glue.implode($glue,$workspace->getSession("postappend"));
		$title	=	$pre.$original.$post;
		
		$workspace->deleteFromSession("preappend");
		$workspace->deleteFromSession("postappend");
		
		return $title;
	}
	static function getTemplate($workspace,$module,$task,$data=false,$html=true){
		$my = $workspace->get("accessHandler")->getAccess();
		
		if($html){ 
			Utils::getViewHeader($workspace,$module,$task,$data);
			Utils::getViewError($workspace,$module,$task,$data);
			Utils::getViewInfo($workspace,$module,$task,$data);
		}
		
		$path=_COMPONENTS_PATH."/".$module."/".$module."-".$task.".view.php";
		if(is_file($path)){
			include($path);
		}
		if($html){ Utils::getViewFooter($workspace,$module,$task,$data);}
	}
	static function getViewError($workspace,$module,$task,$datax){
		$my = $workspace->get("accessHandler")->getAccess();
		
		$data=array();
		$data['dati']=$datax;
		$data[_JEM_ERROR_MESSAGES_] = $workspace->getSession(_JEM_ERROR_MESSAGES_);
		if($data[_JEM_ERROR_MESSAGES_]){ 
		
			$path=_COMPONENTS_PATH."/core/error.php";
			if(is_file($path)){
				include($path);
			}
		
			$workspace->deleteFromSession(_JEM_ERROR_MESSAGES_);
		}
	}
	static function getViewInfo($workspace,$module,$task,$datax){
		$my = $workspace->get("accessHandler")->getAccess();

		$data=array();
		$data['dati']=$datax;
		$data[_JEM_INFO_MESSAGES_] = $workspace->getSession(_JEM_INFO_MESSAGES_);
		if($data[_JEM_INFO_MESSAGES_]){ 
		
			$path=_COMPONENTS_PATH."/core/info.php";
			if(is_file($path)){
				include($path);
			}
		
			$workspace->deleteFromSession(_JEM_INFO_MESSAGES_);
		}
	}
	static function getViewHeader($workspace,$module,$task,$data=false){
		$my = $workspace->get("accessHandler")->getAccess();

		$path=_COMPONENTS_PATH."/core/header.php";
		if(is_file($path)){
			include($path);
		}
	}
	static function getViewFooter($workspace,$module,$task,$data=false){
		$my = $workspace->get("accessHandler")->getAccess();

		$path=_COMPONENTS_PATH."/core/footer.php";
		if(is_file($path)){
			include($path);
		}
	}
	static function getController($controller){
		if(is_file(_CONTROLLERS_SRC."/".$controller."/".$controller.".controller.php")){
			require_once(_CONTROLLERS_SRC."/".$controller."/".$controller.".controller.php");
		}
	}
	static function getModel($model){
		if(is_file(_COMPONENTS_PATH."/".$model."/".$model.".model.php")){
			require_once(_COMPONENTS_PATH."/".$model."/".$model.".model.php");
		}
	}
	static function makeLink($module=false,$task=false,$others=false,$relative=false){
		$array=array();
		$link=Utils::siteOptions("backend");
		if($relative) $link=str_replace(_CONFIG_HTTP,"",$link);
		if($module) $array[]="m=".$module;
		if($task) $array[]="t=".$task;
		
		if($others){
			if(is_array($others)){
				foreach($others as $key => $value){
					$array[]="".$key."=".$value;
				}
			}else{
				$array[] = $others;
			}
		}
		if($array[0]){
			$link.="?".implode("&",$array);
		}
		return $link;
	}
	static function clearFromQueryString($querystring, $remove) {
		if($querystring){
			if(preg_match("/&/i",$querystring)){
				$explo = explode("&", $querystring);
			}else{
				$explo=array($querystring);
			}
			$newstring = "";
			foreach ($explo as $array) {
				if($array){
					list ($key, $value) = explode("=", $array);
					if (!in_array($key, $remove)) {
						$newstring .= ($newstring == "") ? $key . "=" . $value : "&" . $key . "=" . $value;
					}
				}
			}
		}
		return $newstring;
	}
	static function arrayToAttributes($ini_array_data) {
	 
	 	$separator = ' ';
	 	$value_delimiter = "\"";
	 	if(!$ini_array_data || !is_array($ini_array_data)) {
			return "";
		}
		$return = "";
		foreach($ini_array_data as $k => $v) {
			$return .= $k . "=" . $value_delimiter . $v . $value_delimiter . $separator;
		} 
		
		return $return;

	}
	static function cleanUrl($url) {
		if ('' == $url) return $url;
		$url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%]|i', '', $url);
		$strip = array('%0d', '%0a');
		$url = str_replace($strip, '', $url);
		$url = str_replace(';//', '://', $url);
		$url = (!strstr($url, '://')) ? 'http://'.$url : $url;
		$url = preg_replace('/&([^#])(?![a-z]{2,8};)/', '&#038;$1', $url);
	
		return $url;
	}
	static function href ($link, $caption = NULL, $attributes = NULL) {
		
		if(!isset($caption)) {
			$caption = $link;
		}
		$link=str_replace("&","&amp;",$link);
		
		return "<a href=\"" . Utils::cleanUrl($link) . "\" " . Utils :: arrayToAttributes($attributes) . ">" . $caption . "</a>";
		
	}
	static function img ($link, $alt = NULL, $append = NULL) {
	
		
		return "<img src=\"".$link."\" alt=\"".$alt."\" ".$append." />";
		
	}
	static function cropImage($image,$width=false,$height=false,$top=0,$left=0,$alt=false,$append=false){
		$link=Utils::siteOptions("siteurl")."libraries/gd/crop.php?img=".$image;
		if(is_numeric($width)) $link.="&amp;width=".$width;
		if(is_numeric($height)) $link.="&amp;height=".$height;
		if(is_numeric($width)) $text.=" width=\"".$width."\"";
		if(is_numeric($height)) $text.=" height=\"".$height."\"";
		if(is_numeric($top)) $link.="&amp;top=".$top;
		if(is_numeric($left)) $link.="&amp;left=".$left;
		
		if($image) return Utils::img($link, $alt, $text." ".$append);
	}
 	static function scaleImage($image,$width=false,$height=false,$top=0,$left=0,$alt=false,$append=false){
		$link=Utils::siteOptions("siteurl")."libraries/gd/scale.php?img=".$image;
		if(is_numeric($width)) $link.="&amp;width=".$width;
		if(is_numeric($height)) $link.="&amp;height=".$height;
		if(is_numeric($top)) $link.="&amp;top=".$top;
		if(is_numeric($left)) $link.="&amp;left=".$left;
		
		if($image) return Utils::img($link, $alt, $text." ".$append);
	}
 	static function adaptImage($image,$width=false,$height=false,$top=0,$left=0,$alt=false,$append=false){
		$link=Utils::siteOptions("siteurl")."libraries/gd/adapt.php?img=".$image;
		if(is_numeric($width)) $link.="&amp;width=".$width;
		if(is_numeric($height)) $link.="&amp;height=".$height;
		if(is_numeric($top)) $link.="&amp;top=".$top;
		if(is_numeric($left)) $link.="&amp;left=".$left;
		
		if($image) return Utils::img($link, $alt, $text." ".$append);
	}
 	static function makePagination($pagination, $attributes = null) {
		$text = null;

		if ($pagination) {
			$text = "<div id=\"paginazione\">\n";
			$text .= "<ul class=\"paginazione\">\n";
			if ($pagination['first']) {
				$text .= '<li>' . Utils :: href ($pagination['first'], "&laquo; Previous", $attributes) . '</li>' . "\n";
			}

			if ($pagination['back']) {
				$text .= '<li>' . Utils :: href ($pagination['back'], "...", $attributes) . '</li>' . "\n";
			}
			if(is_array($pagination['pages'])){
				foreach ($pagination['pages'] as $page) {
					if ($page['url'] == "") {
						$text .= '<li><span>' . $page['page'] . '</span></li>' . "\n";
					} else {
						$text .= '<li>' . Utils :: href ($page['url'], $page['page'], $attributes) .'</li>' . "\n";
					}
				}
			}

			if ($pagination['next']) {
				$text .= '<li>' . Utils :: href ($pagination['next'], "...", $attributes) . '</li>' . "\n";
			}
			if ($pagination['last']) {
				$text .= '<li>' . Utils :: href ($pagination['last'], "Next &raquo;", $attributes) . '</li>' . "\n";
			}
			$text .= "</ul>\n";
			$text .= "<div class=\"clear\"></div></div>\n";
		}
		return $text;
	}
 	static function nextPage($pagination,$text="next page", $attributes = null) {
		$page = $pagination['actualpage'];
		if ($pagination) {
			if($pagination['maxpage']>$pagination['actualpage']){
				return Utils :: href ($pagination['generalurl']."page=".($page+1), $text, $attributes);
			}
		}
	}
 	static function previusPage($pagination,$text="prev page", $attributes = null) {
		$page = $pagination['actualpage'];
		if ($pagination) {
			if($pagination['actualpage'] > 1){
				return Utils :: href ($pagination['generalurl']."page=".($page-1), $text, $attributes);
			}
		}
	}

 	static function parseDate($data,$format=_LN_SYSTEM_DATA_SHORT){
		$date = new Zend_Date();
      	$date->set($data);
		return strftime($format, $date->get());
	}
	
 	static function convertFromBytes( $bytes, $to=NULL ){
		$float = floatval( $bytes );
		switch( $to ) {
			case 'Kb' :            // Kilobit
				$float = ( $float*8 ) / 1024;
				break;
			case 'b' :             // bit
				$float *= 8;
				break;
			case 'GB' :            // Gigabyte
				$float /= 1024;
			case 'MB' :            // Megabyte
				$float /= 1024;
			case 'KB' :            // Kilobyte
				$float /= 1024;
			default :              // byte
		}
		unset( $bytes, $to );
 		return( $float );
	}
	
 	static function getTimeDifference( $start, $end ){
	    if(!is_numeric($start))$start=strtotime($start);
	    if(!is_numeric($end))$end=strtotime($end);
	    $uts['start']      =    $start;
	    $uts['end']        =    $end;
	    if( $uts['start']!==-1 && $uts['end']!==-1 )
	    {
	        if( $uts['end'] >= $uts['start'] )
	        {
	            $diff    =    $uts['end'] - $uts['start'];
	            if( $days=intval((floor($diff/86400))) )
	                $diff = $diff % 86400;
	            if( $hours=intval((floor($diff/3600))) )
	                $diff = $diff % 3600;
	            if( $minutes=intval((floor($diff/60))) )
	                $diff = $diff % 60;
	            $diff    =    intval( $diff );            
	            return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
	        }
	    }
	    return( false );
	}
	static function callStandardScript($workspace){
		$workspace->addToCSS(_SUB_PATH.'components/core/css/screen.css');
		$workspace->addToCSS(_SUB_PATH.'components/core/css/extra.css');
		$workspace->addToCSS(_SUB_PATH.'components/core/js/ui/personal/jquery-ui-1.8.5.custom.css');
		$workspace->addToCSS(_SUB_PATH.'components/core/js/editor/js/ui-themes/lightness/jquery-ui-1.8.5.custom.css');
		$workspace->addToJS(_SUB_PATH.'components/core/js/jquery.js');
		$workspace->addToJS(_SUB_PATH.'components/core/js/ui/jquery-ui-1.8.5.custom.min.js');
		$workspace->addToJS(_SUB_PATH.'components/core/js/ui/ui.datepicker-it.js');
		$workspace->addToCSS(_SUB_PATH.'components/core/js/ui/jquery.multiselect.css');
		$workspace->addToJS(_SUB_PATH.'components/core/js/ui/jquery.multiselect.min.js');
		//$workspace->addToJS(_SUB_PATH.'components/core/js/editor/js/elrte.full.js');
		//$workspace->addToCSS(_SUB_PATH.'components/core/js/editor/css/elrte.full.css');
		$workspace->addToJS(_SUB_PATH.'components/core/js/ckeditor/ckeditor.js');
		$workspace->addToJS(_SUB_PATH.'components/core/js/ckeditor/adapters/jquery.js');
		$workspace->addToJS(_SUB_PATH.'components/core/js/editor/elfinder/js/elfinder.full.js');
		$workspace->addToCSS(_SUB_PATH.'components/core/js/editor/elfinder/css/elfinder.css');	
	}
	static function writeHeader($workspace){
		
		
		
		$dom = new DomDocument('1.0','iso-8859-15');
		
		$tit_le= $dom->appendChild($dom->createElement('title'));
		$tit_le->appendChild($dom->createTextNode(_CMS_NAME.' - area amministrativa - '._SITE_NAME));
		
		
		if(is_array($workspace->getCSS())){
			foreach($workspace->getCSS() as $css){
				$me_ta = $dom->appendChild(new DOMElement('link'));
				$me_ta->setAttributeNode(new DOMAttr('href', $css));
				$me_ta->setAttributeNode(new DOMAttr('rel', 'stylesheet'));
				$me_ta->setAttributeNode(new DOMAttr('type', 'text/css'));
				$me_ta->setAttributeNode(new DOMAttr('media', 'screen'));
			}
		}
		
		$workspace->addToJS(_SUB_PATH.'components/core/js/core.js');
		if(is_array($workspace->getJS())){
			foreach($workspace->getJS() as $js){
				$me_ta = $dom->appendChild(new DOMElement('script'));
				$me_ta->setAttributeNode(new DOMAttr('src', $js));
				$me_ta->setAttributeNode(new DOMAttr('type', 'text/javascript'));
			}
		}
		
		echo $dom->saveHTML();
	}
	function getCacheName($cache_file){
		$cache_file = preg_replace("(/|:| |\.|\\\\)",'_', $cache_file);
		$cache_file = str_replace(" ",'_', $cache_file);
		$cache_file = str_replace(" ",'_', $cache_file);	
		return $cache_file;
	}
	
	function setThisMenu($key, $row){
		$workspace = JEM::getInstance();
		$m = $workspace->getRequest("m","index");
		$t = $workspace->getRequest("t","view");
		
		$append = Utils::clearFromQueryString($workspace->getServer("QUERY_STRING"),array("m","t","s-title"));
		
		
		if($row["option"] == $m && $row["task"] == $t && ($row["append"] == $append || $row["append"] == "")){
			$workspace->addToSession("actual_menu", $key);
		}else{
			if($row->menu->voice){
				foreach($row->menu->voice as $sub){
					if($sub["option"] == $m && $sub["task"] == $t && ($sub["append"] == $append || $sub["append"] == "")){
						$workspace->addToSession("actual_menu", $key);
					}
				}
			}
		}
	}
	function isThisMenu($key){
		$workspace = JEM::getInstance();
		if($workspace->getSession("actual_menu") ==  $key){
			return true;	
		}
		return false;	
	}

	
	function getImages($text){
		$images = array();
		preg_match_all('/(img|src)=("|\')[^"\'>]+/i', $text, $media);
		$data=preg_replace('/(img|src)("|\'|="|=\')(.*)/i',"$3",$media[0]);
		foreach($data as $url)
		{
			$info = pathinfo($url);
			if (isset($info['extension']))
			{
				if ((strtolower($info['extension']) == 'jpg') ||
				(strtolower($info['extension']) == 'jpeg') ||
				(strtolower($info['extension']) == 'gif') ||
				(strtolower($info['extension']) == 'png'))
				array_push($images, $url);
			}
		}
		//var_dump($images);
		return $images;
	}

	static function cutText($content, $maxchars, $noentities = true, $allows = false){
		$content = strip_tags($content,$allows);
		if($noentities){
			$content = htmlentities($content);
			$content = str_replace("’","&rsquo;",$content);
			$content = str_replace('“',"&ldquo;",$content);
			$content = str_replace('”',"&rdquo;",$content);
		}
		$pos = strlen($content);
		if ($pos>$maxchars) {
			$content = substr($content, 0, $maxchars)."...";
		}
		return $content;	
	}
	static function safeJSON_chars($data) {
		$aux = str_split($data);
		foreach($aux as $a) {
			$a1 = urlencode($a);
			$aa = explode("%", $a1);
			foreach($aa as $v) {
				if($v!="") {
					if(hexdec($v)>127) {
						$data = str_replace($a,"&#".hexdec($v).";",$data);
					}
				}
			}
		}
		return $data;
	}
	function generateHash(){
		$result = "";
		$charPool = '0123456789abcdefghijklmnopqrstuvwxyz';
		for($p = 0; $p<15; $p++)
		$result .= $charPool[mt_rand(0,strlen($charPool)-1)];
		return sha1(md5(sha1($result)));
    }
}