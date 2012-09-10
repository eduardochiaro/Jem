<?php
class Bootstrap {
	
	private $workspace; 
	
	/**
	 * Costruttore sistema 
	 */
	function __construct(){

		// Init
		$this->init_class_path();
		
		error_reporting(E_ALL & ~(E_STRICT|E_NOTICE));
		// Start session
		session_start();
		//timezone
		date_default_timezone_set(_TIME_ZONE);
		
		// Init workspace
		$this->workspace = JEM::getInstance();
		
		if(_IS_ADMIN_ === true){
			$this->workspace->loadHandler("access","auto");
			$this->workspace->loadComponent("index");
		}else{
			new Frontend($this->workspace);	
		}
	}
	
	function returnWorkspace(){
		return $this->workspace;	
	}
	function init_class_path() {
		
		// Costanti
		require(dirname(__FILE__)."/../includes/config.php");
		require(dirname(__FILE__)."/../includes/extra.config.php");
		
		/*includes*/
		require(_INCLUDES_PATH."/filesystem.php");
		require(_INCLUDES_PATH."/formatting.php");
		require(_INCLUDES_PATH."/version.php");    
		require(_INCLUDES_PATH."/log.php");  
	
		// Includo compatibilita versioni PHP 
		require(_INCLUDES_PATH."/compatibility.php");	
		
		/*classes*/
		//$this -> recursive_include(_CORE_CLASSES_PATH, "\.php$");
		require(_CORE_CLASSES_PATH."/framework.php"); 
		require(_CORE_CLASSES_PATH."/component.class.php"); 
		require(_CORE_CLASSES_PATH."/controller.class.php"); 
		require(_CORE_CLASSES_PATH."/frontend.class.php"); 
		require(_CORE_CLASSES_PATH."/handler.class.php"); 
		require(_CORE_CLASSES_PATH."/mailer.class.php"); 
		require(_CORE_CLASSES_PATH."/model.class.php"); 
		require(_CORE_CLASSES_PATH."/paginations.class.php"); 
		require(_CORE_CLASSES_PATH."/utils.class.php"); 
		
		/*html*/
		//$this -> recursive_include(_HTML_CLASSES_PATH, "\.php$");
		require(_HTML_CLASSES_PATH."/check.php"); 
		require(_HTML_CLASSES_PATH."/forms.php"); 
		require(_HTML_CLASSES_PATH."/formuploads.php"); 
		require(_HTML_CLASSES_PATH."/scaleandsave.php"); 
		require(_HTML_CLASSES_PATH."/upload.php"); 
		
		/*libraries*/
		set_include_path(implode(PATH_SEPARATOR, array(
			_LIBRARIES_PATH,
			get_include_path()
		)));
		require(_LIBRARIES_PATH."/Zend/Loader/Autoloader.php");
		require(_LIBRARIES_PATH."/translations/translations.php");	 
		require(_LIBRARIES_PATH."/phpmailer/class.phpmailer.php"); 	
		require(_LIBRARIES_PATH."/video/youtube.php"); 	  
		require(_LIBRARIES_PATH."/backup/iam_backup.php"); 	
		require(_LIBRARIES_PATH."/backup/zipfile.php"); 	

		
		// Filtri
		//$this -> recursive_include(_LIBRARIES_PATH."/filters/", "\.php$");
		
		require(_LIBRARIES_PATH."/filters/filter.php"); 
		require(_LIBRARIES_PATH."/filters/concatfilter.php");
		require(_LIBRARIES_PATH."/filters/comparefilter.php");
		require(_LIBRARIES_PATH."/filters/betweenfilter.php");
		require(_LIBRARIES_PATH."/filters/andfilter.php");
		require(_LIBRARIES_PATH."/filters/falsefilter.php");
		require(_LIBRARIES_PATH."/filters/infilter.php");
		require(_LIBRARIES_PATH."/filters/likefilter.php");
		require(_LIBRARIES_PATH."/filters/notfilter.php");
		require(_LIBRARIES_PATH."/filters/orfilter.php");	
		require(_LIBRARIES_PATH."/filters/setfilter.php");	
		require(_LIBRARIES_PATH."/filters/truefilter.php");	


		// Controllers, Models, frontend, Handlers
		$this -> recursive_include(_COMPONENTS_PATH, array("\.controller.php$","\.model.php$","\.frontend.php$","\.handler.php$"));




	}

	/**
	 * Include ricorsivamenti i file presenti nel sottoalbero del
	 * $base_path che risponsando al pattern
	 */
	function recursive_include($base_path, $pattern=".") {
		
		// Catturo tutti i file a partira dalla base_dir
		$required = FileSystem::files($base_path, $pattern, true, true);

		if(is_array($required)){
			foreach ($required as $filename) {
				// Includo file
				require_once($filename);
	
			}
		}
	}
	
}