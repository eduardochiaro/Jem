<?php

/**
 * Controllo valore in lista elementi 
 */
class BetweenFilter implements Filter {
 	
 	/**
 	 * Colonna
 	 */
 	private $column;
 	 	
 	/**
 	 * Lista valori
 	 */
 	private $set1;
 	
 	private $set2;
 	
 	/**
	 * Costruttore
	 */
	function __construct($column, $set1, $set2){
		$this -> column = $column;
		$this -> set1 = $set1;
		$this -> set2 = $set2;
	} 		
	 	
 	/**
 	 * Ritorna costrutto SQL del filtro
 	 */
 	function toSQL() {
 		return  $this -> column . " BETWEEN '" . $this -> set1 . "' AND '" . $this -> set2 . "'";
 	}
}
?>
