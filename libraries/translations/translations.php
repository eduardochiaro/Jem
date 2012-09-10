<?php
class Translations{
	
	protected  $self = array(
							 "ACTIVE" => true,
							 "VIEW_ALL" => false,
							 "DB_DATABSE" => false,
							 "TABLE_LANGUAGES" => "ls_languages",
							 "TABLE_TRANSLATIONS" => "ls_translations"
							 );
	protected $myconn = false;
	protected $actual = null;
	protected $language = null;
	protected $getkeys = true;
	private $workspace;
	
    public function __construct( /*array*/$workspace, $itms = array() ) {
		$this->setWorkspace($workspace);

		foreach( $itms as $name => $enum ){
            $this->add($name, $enum);
		}
		
		$this->myconn = $workspace->getDatabase();

		$this->setActual();
		
		if(is_file(dirname(__FILE__)."/languages/".$this->actual['code'].".php")) include_once(dirname(__FILE__)."/languages/".$this->actual['code'].".php");
		$this->language = $language;
    }

	function getWorkspace(){
		return $this->workspace;
	}
	
	function setWorkspace($workspace){
		$this->workspace=$workspace;
	}
	
    public function __get($name = null ) {
        return $this->self[$name];
    }
	
    public function isActive() {
        return true;
    }
	
    public function code() {
        return $this->actual["code"];
    }
	
    public function __set($name = null, $enum = null ) {
        return $this->self[$name] = $enum;
    }
   
    public function add($name = null,  $enum = null ) {
		if(array_key_exists($name,$this->self)){
			if( isset($enum)){
				$this->__set($name ,$enum);
			}else{
				$this->__set($name ,0);
			}
		}
    }
	private function getLanguage(){
		return ($_SESSION['ls_lang_active'])?$_SESSION['ls_lang_active']:$this->actual['id'];
	}
	 function getLanguageFromId($lang){
			$select = $this->myconn->select("*")->from($this->__get("TABLE_LANGUAGES"));
			$select->where("active='1' AND id='".$lang."'");
			$select->limit(1);
			$select = $this->myconn->fetchRow($select); 
			
			return $select;
	}
	private function setActual(){
		
		if($_GET['lang']){
			$select = $this->myconn->select("*")->from($this->__get("TABLE_LANGUAGES"));
			$select->where("active='1' AND code='".$_GET['lang']."'");
			$select->limit(1);
		}
		
		if(!isset($_GET['lang']) || !count($this->myconn->fetchAll($select))){
			$select = $this->myconn->select("*")->from($this->__get("TABLE_LANGUAGES"));
			$select->where("active='1' AND actual='1'");
			$select->limit(1);
		}
		
		$select = $this->myconn->fetchRow($select); 
		$_SESSION['ls_lang_active'] = $select['id'];
		$this->actual = $select;
	}
	function getActual(){
		return $this->actual;
	}
	private function makeField($args = array(), $idlanguage, $count, $data = array(), $model = false, $append = "", $nop = false){
		
		$actual = $this->actual;
		
		if(trim($data['text_'.$count])){
			$text = $data['text_'.$count];
		}else{
			$text = $args['standard'];
		}
		
		
 		$req = "";
		if($args['required']) $req = " required";
		if($args['class']) $req = " ".$args['class'];
		if($args['pretext']) $pretext = $args['pretext'];
		if($args['posttext']) $posttext = $args['posttext'];
 		
		$_forms = new Forms($this->getWorkspace());
		
		if(!$nop) $field = '<p class="lungo-1">'."\n";
			
			switch($args['type']){
				case "text":
					$field .= $_forms->element($args['text'].$append, $args['id'].'-'.$idlanguage, $pretext.$_forms->text('text_'.$model.'_'.$count.'-'.$idlanguage,$text,"lungo-1".$req,'maxlength="220" size="20" rel="'. $model .'" lang="'.$idlanguage.'"').$posttext,"generica");
                   	//$field .= '<input type="text" name="text_'.$model.'_'.$count.'-'.$idlanguage.'" id="'.$args['id'].'-'.$idlanguage.'" value="'.$text.'" class="lungo-1'.$req.'" size="" />'."\n";
					break;
				case "textarea":
					if(get_magic_quotes_gpc()) $text = stripslashes($text);
					$class = ($args['editor'])?"editor":"lunga-2";
					$class = ($args['editor-full'])?"editor-full":$class;
					$field .= $_forms->element($args['text'].$append, $args['id'].'-'.$idlanguage, $pretext.$_forms->textarea('text_'.$model.'_'.$count.'-'.$idlanguage,5,10,$text, $class).$posttext,"generica");
                 	//$field .= '<textarea name="text_'.$model.'_'.$count.'-'.$idlanguage.'" id="'.$args['id'].'-'.$idlanguage.'" class="'.$class.$req.'" cols="" rows="">'.$text.'</textarea>'."\n";
					break;
			}
			
		if(!$nop) $field .= '</p>'."\n";	
		
		return $field;
	}
	public function getAllLanguages(){
		$select = $this->myconn->select("*")->from($this->__get("TABLE_LANGUAGES"));
		$select->where("active='1'");
		$select->order("actual DESC");
		
		$smt = $this->myconn->query($select); 
		$rows = $smt->fetchAll();
		
		return $rows;
	}
	public function makeTabs($args = array(), $model = false, $reference = false){
		
		$tabs = array();
		$content = array();
		
		foreach($this->getAllLanguages() as $row){
			
			$tabs[] = '<li><a href="#'.$model.'-language-'.$row['id'].'">'.$row['language'].'</a></li>'."\n";
			
			$fields = array();
			
		
			if((int)$reference){
				$select = $this->myconn->select("*")->from($this->__get("TABLE_TRANSLATIONS"));
				$select->where("language = '".$row['id']."' AND model='".$model."' AND reference='".$reference."'");
				$select->limit(1);
				$data = $this->myconn->fetchRow($select);
			}
			
			
			$x = 1;
			$ruleReq = array();
			$textReq = array();
			foreach($args as $arg){
				$fields[] = $this->makeField($arg,$row['id'], $x, $data,$model);
				$x++;
			}
			$cont = '';
			$cont .= '<fieldset id="fieldset-'.$model.'-'.$row['id'].'">'."\n";
				$cont .= '<div id="'.$model.'-language-'.$row['id'].'">'."\n";
					$cont .= implode("",$fields);
				$cont .= '</div>'."\n";
			$cont .= '</fieldset>'."\n";
			$content[] = $cont;
		}
		
		echo '<div class="call-tabs">'."\n";
			echo '<div class="call-tabs-'.$model.'-'.$row['id'].'">'."\n";
				echo '<ul>'."\n";
					echo implode("",$tabs);
				echo '</ul>'."\n";
				echo '<div class="contents-lang">'."\n";
					echo implode("",$content);
				echo '</div>'."\n";
			echo '</div>'."\n";
		echo '</div>'."\n";
		
		echo '<script type="text/javascript">'."\n";
			
			echo' $(document).ready(function() {'."\n";
				echo '$(".call-tabs-'.$model.'-'.$row['id'].'").tabs({fxFade: true, fxSpeed: "fast"});'."\n";
				
				if(isset($ruleReq[0])){
					
					echo 'jQuery.validator.addMethod("decimalTwo", function(value, element) {'."\n";
					echo '	return this.optional(element) || /^(\d{1,3})(\,\d{2})$/.test(value);'."\n";
					echo '	}, "Must be in US currency format 0.99"); '."\n"; 
					echo '	jQuery.extend(jQuery.validator.messages, {'."\n";
					echo '	required: "Questo campo e obbligatorio.",'."\n";
					echo '	remote: "Riempire questo campo per continuare.",'."\n";
					echo '	email: "Inserire un indirizzo email valido.",'."\n";
					echo '	url: "Inserire un indirizzo URL valido.",'."\n";
					echo '	date: "Inserire una data in formato mm-gg-aaaa.",'."\n";
					echo '	dateDE: "Inserire una data in formato gg-mm-aaaa.",'."\n";
					echo '	dateISO: "Inserire una data in formato aaaa-mm-gg.",'."\n";
					echo '	number: "Inserire un numero valido.",'."\n";
					echo '	digits: "Inserire (solo) un numero.",'."\n";
					echo '	creditcard: "Inserire un numero di carta di credito valido.",'."\n";
					echo '	equalTo: "Inserire lo stesso valore usato sopra.",'."\n";
					echo '	decimalTwo: "Deve essere un numero con due cifre decimali (es. 200,99)",'."\n";
					echo '	accept: "Usare un\'estensione valida.",'."\n";
					echo '	maxlength: jQuery.format("Inserire al massimo {0} caratteri."),'."\n";
					echo '	minlength: jQuery.format("Inserire almeno {0} caratteri."),'."\n";
					echo '	rangelength: jQuery.format("Inserire da {0} a {1} caratteri."),'."\n";
					echo '	range: jQuery.format("Inserire un numero compreso tra {0} e {1}."),'."\n";
					echo '	max: jQuery.format("Inserire un numero minore o uguale a {0}."),'."\n";
					echo '	min: jQuery.format("Inserire un numero maggiore o uguale a {0}.")'."\n";
					echo '});'."\n";
					
					echo '$("#myform").validate();'."\n";
					
				}
			echo '});'."\n";
		echo '</script>'."\n";
	}
	public function makeInline($args = array(), $model = false, $reference = false){
		
		$tabs = array();
		$content = array();
		
		foreach($this->getAllLanguages() as $row){
			
			$tabs[] = '<li><a href="#language-'.$row['id'].'">'.$row['language'].'</a></li>'."\n";
			
			$fields = array();
			
		
			if((int)$reference){
				$select = $this->myconn->select("*")->from($this->__get("TABLE_TRANSLATIONS"));
				$select->where("language = '".$row['id']."' AND model='".$model."' AND reference='".$reference."'");
				$select->limit(1);
				$data = $this->myconn->fetchRow($select);

			}
			
			
			$x = 1;
			$ruleReq = array();
			$textReq = array();
			foreach($args as $arg){
				$fields[] = $this->makeField($arg,$row['id'], $x, $data,$model," in ".$row['language']);
				$x++;
			}
			$cont = '';
			$cont .= implode("",$fields);
			echo $cont;
		}
		
	}
	
	public function saveData($args = array(), $model = false, $reference = false){
		if($this->isActive()){
			$select = $this->myconn->select("*")->from($this->__get("TABLE_LANGUAGES"));
			$select->where("active='1'");
			$select->order("actual DESC");
			
			$smt = $this->myconn->query($select); 
			$rows = $smt->fetchAll();
			
			
			foreach($rows as $row){
				$select = $this->myconn->select("id")->from($this->__get("TABLE_TRANSLATIONS"));
				$select->where("language = '".$row['id']."' AND model='".$model."' AND reference='".$reference."'");
				
				$smt = $this->myconn->query($select); 
				$connrow = $smt->fetchAll();
				
				if(count($connrow)){
					$list = array();
					for($i=1; $i<=10; $i++){
						$list['text_'.$i] = $args['text_'.$model.'_'.$i.'-'.$row['id']];
					}
					
					$where = $this->myconn->quoteInto("language = ? AND ", $row['id']);
					$where .= $this->myconn->quoteInto("model = ? AND ", $model);
					$where .= $this->myconn->quoteInto("reference = ?", $reference);

					$this->myconn->update($this->__get("TABLE_TRANSLATIONS"), $list, $where);
					
					//$this->myconn->sql("UPDATE ".$this->__get("TABLE_TRANSLATIONS")." SET ".implode(", ",$list)." WHERE language = '".$row['id']."' AND model='".$model."' AND reference='".$reference."'");
					//$this->myconn->query();
	
				}else{
	
					$list = array(
								  "language" => $row['id'],
								  "model" => $model,
								  "reference" => $reference
								  );
					for($i=1; $i<=10; $i++){
						$list['text_'.$i] = $args['text_'.$model.'_'.$i.'-'.$row['id']];
					}
					
					
					$this->myconn->insert($this->__get("TABLE_TRANSLATIONS"), $list);
	
					/*
					$this->myconn->sql("INSERT INTO ".$this->__get("TABLE_TRANSLATIONS")." (language,model,reference,".implode(", ",$list).")VALUES('".$row['id']."','".$model."','".$reference."',".implode(", ",$save).")");
					$this->myconn->query();
					*/
				}
			}
		}
	}
	public function getFromStandard($args = array(),$model=false){
		$active = $this->actual;
		$list = array();

		for($i=1; $i<=10; $i++){
			$list[] = $args['text_'.$model.'_'.$i.'-'.$active['id']];
		}
		return $list;
	}
	public function deleteData( $model = false, $reference = false){

		
		
		$where = $this->myconn->quoteInto("model = ? AND ", $model);
		$where .= $this->myconn->quoteInto("reference = ?", $reference);
		
		$remove = $this->myconn->delete($this->__get("TABLE_TRANSLATIONS"), $where);
		
		return $remove;
	}
	
	
	public function getData($element, $language = false){
		
		
		$model		= $element->getTable();
		$reference	= $element->id;
		$arg_list	= $element->getTranslateFields();
		
				
		
		$search = array();
		$result = array();
		$new = array();
		
		$i = 1;

		// Se info nullo
		if(!isset($arg_list)) {
			
			// Esco
			return;
		}
		foreach($arg_list as $key => $arg) {
			$search[] = "text_".($i);
			$result[] = $key;
			$i++;
		}
		
		if(!$language){
			$language = $this->getLanguage();
		}
		
		if($search[0]){
			
			$select = $this->myconn->select()->from($this->__get("TABLE_TRANSLATIONS"),$search);
			$where = "language = '".$language."' AND model='".$model."' AND reference='".$reference."'";
			
			$select->where($where);
			$select->limit(1);
			
			$row = $this->myconn->fetchRow($select); 
			
			if(is_array($row)){
				$new = array_combine($result, $row);
			}
		}
		
		return ($new);
	}
	function e(){
    	$arg_list = func_get_args();
		
		$code		= strtolower($arg_list[0]);
		array_shift($arg_list);

		if($this->getkeys){
			session_start();
			if(!isset($_SESSION['ls_keys'][$code])){
				$_SESSION['ls_keys'][$code] = $code;
			}
		}
		if(isset($this->language[$code]) && $this->language[$code]!=""){
			$lcode = $this->language[$code];
			if(isset($arg_list[0])) $lcode = vsprintf ($lcode, $arg_list);
			return $lcode;	
		}else{
			$lcode = $code;
			if(isset($arg_list[0])) $code = vsprintf ($lcode, $arg_list);
			return $code;	
		}
	}
	function printKeys(){
		session_start();
		//unset($_SESSION['ls_keys']);
		$ls_key_arr = $_SESSION['ls_keys'];
		asort($ls_key_arr,SORT_STRING );
		foreach($ls_key_arr as $key=>$value){
			echo '	"'.addslashes($key).'"	=>	"'.addslashes($value).'",'."\n";
		}
	}
}

?>