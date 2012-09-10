<?php

/**
 * Filtro che vale falso, sempre
 */
class FalseFilter implements Filter {
 	
 	/**
	 * Costruttore
	 */
	function __construct(){
	} 		
	 	
 	/**
 	 * Ritorna costrutto SQL del filtro
 	 */
 	function toSQL() {
 		return "0";
 	}
}
?>
