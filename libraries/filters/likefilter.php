<?php

define('_LIKE_ALL', "%?%");
define('_LIKE_SX', "%?");
define('_LIKE_DX', "?%");
define('_LIKE_STRICT', "?");

/**
 * Controllo like tra 2 elementi
 */
class LikeFilter implements Filter {
 	
 	/**
 	 * Colonna
 	 */
 	private $column;
 	 	
 	/**
 	 * Valore
 	 */
 	private $value;
 	
 	/**
 	 * Mode
 	 */
 	private $mode;
 	
 	/**
	 * Costruttore
	 */
	function __construct($column, $value, $mode = _LIKE_ALL){
		$this -> column = $column;
		$this -> value = $value;
		$this -> mode = $mode;
	} 		
	 	
 	/**
 	 * Ritorna costrutto SQL del filtro
 	 */
 	function toSQL() {
 		return $this -> column . " LIKE '" . str_replace("?", $this -> value, $this -> mode) . "'";
 	}
}
?>
