<?php
/**
 * Filtro che vale vero, sempre
 */
class TrueFilter implements Filter {
 	
 	/**
	 * Costruttore
	 */
	function __construct(){
	} 		
	 	
 	/**
 	 * Ritorna costrutto SQL del filtro
 	 */
 	function toSQL() {
 		return "1";
 	}
}
?>
