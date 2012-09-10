<?php


/**
 * Controllo in
 */
class InFilter implements Filter {
 	
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
	function __construct($column, $value){
		$this -> column = $column;
		$this -> value = $value;
	} 		
	 	
 	/**
 	 * Ritorna costrutto SQL del filtro
 	 */
 	function toSQL() {
 		return $this -> column . " IN ('" . implode("', '", $this -> value) . "')";
 	}
}
?>
