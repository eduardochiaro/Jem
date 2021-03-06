<?php
/**
 * Controllo valore in lista elementi 
 */
class SetFilter implements Filter {
 	
 	/**
 	 * Colonna
 	 */
 	private $column;
 	 	
 	/**
 	 * Lista valori
 	 */
 	private $set;
 	
 	/**
	 * Costruttore
	 */
	function __construct($column, $set){
		$this -> column = $column;
		$this -> set = $set;
	} 		
	 	
 	/**
 	 * Ritorna costrutto SQL del filtro
 	 */
 	function toSQL() {
 		return $this -> column . " IN (" . implode(",", $this -> set) . ")";
 	}
}
?>
