<?php

class JEM{

	var $workspace;
	
	private function __construct(){
		$time_start = microtime(true);
		$this->ini_env();
		$this->workspace = array ();
		
		
		$zend = Zend_Loader_Autoloader::getInstance();
		
		$db = Zend_Db::factory(_DB_ADAPTER,array(
											'host'     => _DB_HOST,
											'username' => _DB_USER,
											'password' => _DB_PASSWORD,
											'dbname'   => _DB_NAME
										));
		Zend_Cache_Backend_File::setCacheDir("/tmp/");
		#
		// turn on profiler:
		#
		$db->getProfiler()->setEnabled(true);
		//Switching to debug mode 
		//$db->debug_mode(); 
		
		
		$this->set(_JEM_DB_, $db);
		$this->set('zend', $zend);
		$this->set(_JEM_TIME_, $time_start);
		
		$translations = new Translations($this);
		$this->set(_JEM_TRANSLATION_, $translations);
		
		
		$model=new Config($this);
		$model->getSingle("*",array("id"=>1));
		
		if(!defined(_SITE_NAME))define(_SITE_NAME,$model->sitename);
		if(!defined(_LN_SYSTEM_DATA_SHORT))define(_LN_SYSTEM_DATA_SHORT,$model->timeformat);
		
		Utils::callStandardScript($this);
		
	}

	/**
	 * Ritorna workspace, eventualmene inizializzandolo
	 */
	static function getInstance() {
		static $instance;
		
		if(!isset($instance)) {
			$instance = new JEM();
		}
		
		return $instance;
	}
	
	function loadComponent($standard){
		new Component($this,true,$standard);
	}
	
	function ini_env() {
		// Locale
		//setlocale(LC_ALL, _LC_ALL_1_, _LC_ALL_2_, _LC_ALL_3_, _LC_ALL_4_);
		//setlocale(LC_MONETARY, _LC_MONETARY_);
	}

	function get($key) {
		return $this->workspace[$key];
	}

	function set($key, $value) {
		$this->workspace[$key] = $value;
	}

	function keys() {
		return array_keys($this->workspace);
	}


	function getDatabase() {
		return $this->get(_JEM_DB_);
	}

	function getTranslation() {
		return $this->get(_JEM_TRANSLATION_);
	}

	function getDatabaseError() {
		$db = $this->getDatabase();
		
		$q = $db->getProfiler()->getLastQueryProfile()->getQuery();
		$w = $db->getProfiler()->getLastQueryProfile()->getQueryParams();
		
		$sql = $db->quote($q, $w);

		return$sql;

	}
	
	/* lista css e js da aggiungere a header*/
	
	function getCSS() {
		return $this->get("CSS");
	}
	function getJS() {
		return $this->get("JS");
	}
	
	function addToCSS($value) {
		$css =  $this->get("CSS");
		$css[] = $value;
		$this->set("CSS",$css);
	}
	
	function addToJS($value) {
		$js =  $this->get("JS");
		$js[] = $value;
		$this->set("JS",$js);
	}
	
	/**
	 * Cerca il parametro in input in request.
	 * Se e assente lo cerca in session.
	 * Ultimo passo e settare in session il valore da restituire
	 * Crea un registro di workspace 
	 */
	function getWorkspace($key = null, $default = null) {
		
		// Se richiesto parametro
		if (isset ($key)) {
	
			// Cerco in request
			$return = $this -> getRequest($key);
			
			// Se non trovato
			if(!isset($return)) {
				
				// Cerco in session
				$return = $this -> getSession($key, $default);
			}
			
			// Aggiungo alla sessione
			if(!$default || $return !== $default) {
				$this -> addToSession($key, $return);
			}
			
			return $return;

		} else {
			
			$session = $this -> getSession();
			$req = $this -> getRequest();
			return array_merge($session, $req);
		}
	}
	
	function getGet($key = null, $default = null) {
		return $this -> __getFromArray($_GET, $key, $default);
	}

	function getPost($key = null, $default = null) {
		return $this -> __getFromArray($_POST, $key, $default);
	}

	function getRequest($key = null, $default = null) {
		return $this -> __getFromArray($_REQUEST, $key, $default);
	}
	
	function getFiles($key = null, $default = null) {
		return $this -> __getFromArray($_FILES, $key, $default);
	}

	/**
	 * Ritorna la sessione o, se specificato il parametro key,
	 * l'oggetto di sessione con essa identificata
	 * @param string key chiave oggetto in sessione
	 */
	function getSession($key = null, $default = null) {
		return $this -> __getFromArray($_SESSION, $key, $default);
	}

	function getServer($key = null, $default = null) {
		return $this -> __getFromArray($_SERVER, $key, $default);
	}

	function __getFromArray($array, $key = null, $default = null) {
		if (isset ($key)) {
			if (isset ($array[$key])) {
				return $array[$key];
			}
			
			return $default;
		}
		return $array;
	}

	/**
	 * Aggiunge una variabile in workspace
	 */
	function addToWorkspace($key, $value) {
		$this -> addToSession($key, $value);
	}
	
	/**
	 * Aggiunge una variabile in sessione
	 */
	function addToSession($key, $value) {
		$_SESSION[$key] = $value;
	}
	
	/**
	 * Aggiunge una variabile in get
	 */
	function addToGet($key, $value) {
		$_GET[$key] = $value;
		$this -> addToRequest($key, $value);
	}
	
	/**
	 * Aggiunge una variabile in post
	 */
	function addToPost($key, $value) {
		$_POST[$key] = $value;
		$this -> addToRequest($key, $value);
	}
	function deleteFromPost($key = false) {
		if($key){
			unset($_POST[$key]);
		}else{
			unset($_POST);
		}
	}
	
	/**
	 * Aggiunge una variabile in request
	 */
	function addToRequest($key, $value) {
		$_REQUEST[$key] = $value;
	}
	
	/**
	* Aggiunge una variabile in sessione
	*/
	function addToArrayInSession($array_key, $add_value) {

		// Catturo array in sessione
		$array = $this->getSession($array_key);
		if (!isset ($array)) {
			$array = array ();
		}

		// Aggiungo elemento
		if ($add_value) {
			$array[] = $add_value;
		}

		// Risetto array in sessione
		$this->addToSession($array_key, $array);
	}
	function preAppendTitle($text){
		$this->addToArrayInSession("preappend",$text);
	}
	function postAppendTitle($text){
		$this->addToArrayInSession("postappend",$text);	
	}

	/**
	 * Aggiunge una variabile in sessione
	 */
	function deleteFromSession($key) {
		unset ($_SESSION[$key]);
	}
	
	function addErrorMessage($text){
		$this->addToArrayInSession(_JEM_ERROR_MESSAGES_,$text);
	}
	
	function addInfoMessage($text){
		$this->addToArrayInSession(_JEM_INFO_MESSAGES_,$text);
	}
	
	function addFrontErrorMessage($text){
		$this->addToArrayInSession(_JEM_FRONT_ERROR_MESSAGES_,$text);
	}
	
	function addFrontInfoMessage($text){
		$this->addToArrayInSession(_JEM_FRONT_INFO_MESSAGES_,$text);
	}
	

	/**
	 * Errori dei field
	 */
	function addFormError($field){
		$f = $this->get("errorfields");
		$f[] = $field;
		$this->set("errorfields", $f);
	}
	
	function getFormError(){
		$err = $this->get("errorfields");
		$err = (is_array($err))?$err:array();
		return $err;
	}

	/**
	 * Ritorna il numero di query eseguite
	 */
	
	function getQueryNumber(){
		$db=$this->getDatabase();
		return $db->returnQueryCount();
	}
	
	function getScriptTime(){
		$time_end = microtime(true);
		$time = $time_end - $this->get(_JEM_TIME_);
		return sprintf("%01.4F", $time);
	}
	
	
	/**
	 * Carica un Handler
	 */
	function loadHandler($handler,$call="exec",$parameters=false){
		$class=$handler."Handler";
		if(is_callable(array($class,$call))){
			$newclass =  new $class($this);
			$this->set($class,$newclass);
			return $newclass->$call($parameters);
		}else{
			return false;
		}
	}
}