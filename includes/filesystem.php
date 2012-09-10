<?php

/**
 * Classe per log system
 * Wrapper della classe Log (http://www.phpclasses.org/browse/package/2715.html) 
 * rinominata LogSys, avendo gi‡ introdotto la classe Log
 */
class FileSystem {

	/**
	 * Utility function to read the files in a folder
	 *
	 * @param	string	$path		The path of the folder to read
	 * @param	string	$filter		A filter for file names
	 * @param	mixed	$recurse	True to recursively search into sub-folders, or an integer to specify the maximum depth
	 * @param	boolean	$fullpath	True to return the full path to the file
	 * @param	array	$exclude	Array with names of files which should not be shown in the result
	 * @return	array	Files in the given folder
	 * @since 1.5
	 */
	static function files($path, $filter = '.', $recurse = false, $fullpath = false, $exclude = array('.svn', 'CVS')){
		// Initialize variables
		$arr = array ();


		// Check to make sure the path valid and clean
		$path = FileSystem::cleanPath($path);
		
		
		// Is the path a folder?
		if (!is_dir($path)) {
			Log::error($path . " non é una directory");
			return false;
		}
		$ite=new RecursiveDirectoryIterator($path);

		foreach (new RecursiveIteratorIterator($ite) as $filename => $cur) {
			if(is_array($filter)){
				foreach($filter as $fl){
					if (preg_match("/$fl/", $filename)) {
						if(!$fullpath) $filename = str_replace($path,"", $filename);
						$arr[] = $filename;
					}
				}
			}else{
				if (preg_match("/$filter/", $filename)) {
					if(!$fullpath) $filename = str_replace($path,"", $filename);
					$arr[] = $filename;
				}
			}
		}
		
		asort($arr);
		return $arr;
	}
	
	function loadFile($file){
		$load = file_get_contents($file);	
		return $load;
	}

	/**
	 * Function to strip additional / or \ in a path name
	 *
	 * @static
	 * @param	string	$path	The path to clean
	 * @param	string	$ds		Directory separator (optional)
	 * @return	string	The cleaned path
	 * @since	1.5
	 */
	static function cleanPath($path, $ds = _PATH_SEPARATOR){
		$path = trim($path);

		// Remove double slashes and backslahses and convert all slashes and backslashes to DS
		$path = preg_replace('#[/\\\\]+#', $ds, $path);

		return $path;
	}
	
	/**
	 * Scrive il contenuto della variabile $txt nel file $file
	 * Se $file non esiste viene creato
	 * Se esiste allora viene sovrascritto (se $mode = 'w' - default) 
	 * o il testo Ë aggiunto (se $mode = 'a')
	 */
	static function writeFile($txt, $file, $mode = 'w') {
		
		// Apro file
		if (!$handle = fopen($file, $mode)) {
			
			// Errore
			return false;
		}
		
		// Scrivo contenuto
		if (!fwrite($handle, $txt)) {
			
			// Errore
			return false;

		}
		
		// Chiudo file
		fclose($handle);
		
		return true;
	}
	
	/**
	* cerca ed elimina determinati file cercando nel nome
	*/
	static function searchAndDestroy($path,$search){
		if($search){
			$files=FileSystem::files($path);
			$search = basename($search);

			foreach($files as $file){
				if(preg_match("/".$search."/i",$file)){ 
					if(!unlink($path._PATH_SEPARATOR.$file)){
						die($file);
					}
				}
			}
		}
	}
	
	/**
	* leggi i commenti php del file
	*/
	function loadComments($filename){
		$r = new Zend_Reflection_File($filename);
		
		$docBlock  = $r->getDocComment();

		// read method tags
		$code = new Zend_Reflection_Docblock($docBlock);
		
		$tags = $code->getTags();
		
		foreach($tags as $tag){
			$_tags[trim($tag->getName())] = trim($tag->getDescription());
		}
		
		return $_tags;
	}
}