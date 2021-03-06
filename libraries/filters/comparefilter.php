<?php

define('_FILTER_EQ', "=");
define('_FILTER_NQ', "<>");
define('_FILTER_LT', "<");
define('_FILTER_GT', ">");
define('_FILTER_LE', "<=");
define('_FILTER_GE', ">=");

/**
 * Filtro per comparare 2 valore in base al confronto definito dal parametro op
 */
class CompareFilter implements Filter {
 	
 	/**
 	 * Primo valore
 	 */
 	private $v1;
 	
 	/**
 	 * Secondo valore
 	 */
 	private $v2;

 	/**
 	 * Compare op
 	 */
 	private $op;
 	 	
 	/**
	 * Costruttore
	 */
	function __construct($v1, $v2, $op = _FILTER_EQ){
		$this -> v1 = $v1;
		$this -> v2 = $v2;
		$this -> op = $op;
	} 		
	 	
 	/**
 	 * Ritorna costrutto SQL del filtro
 	 */
 	function toSQL() {
 		return $this -> v1 . " " . $this -> op . " " . $this -> v2;
 	}
}
?>
