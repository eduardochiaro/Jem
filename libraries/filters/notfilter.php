<?php

/**
 * Filtro che nega il filtro in input al costruttore
 * mediante operatore logico NOT
 */
class NotFilter implements Filter {
 	
 	/**
 	 * filtro
 	 */
 	private $f;
 	
 	/**
	 * Costruttore
	 */
	function __construct($f){
		if(!($f instanceof Filter)) {
			die("param must be a Filter");
		}
		
		$this -> f = $f;

	} 		
	 	
 	/**
 	 * Ritorna costrutto SQL del filtro
 	 */
 	function toSQL() {
 		return "NOT (" . $this -> f -> toSQL() . ")";
 	}
}
?>
