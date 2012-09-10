<?php
require_once (_LIBRARIES_PATH."/filters/filter.php");

/**
 * Filtro che concatena più filtri in input al costruttore
 * mediante operatore ritornato dal metodo getOp()
 */
abstract class ConcatFilter implements Filter {

	/**
	 * Lista filtri
	 */
	private $fs;

	/**
	* Costruttore
	*/
	function __construct() {

		/**
		 * Ciclo sui parametri
		 */
		for ($i = 0; $i < func_num_args(); $i++) {
			$f = func_get_arg($i);
			if (!($f instanceof Filter)) {
				die("param must be a Filter");
			}
			
			// Aggiungo filto
			$this->fs[] = $f;
		}
	}

	/**
	 * Ritorna costrutto SQL del filtro
	 */
	function toSQL() {
		
		// Ciclo sui filtri
		$sql = "";
		foreach($this->fs as $f) {
			$sql .= ($sql != "" ? " " . $this -> getOp() . " " : "") . "(" . $f->toSQL() . ")";
		}
		
		return $sql;
	}
	
	/**
	 * Metodo che ritorna l'operatore da utilizzare come 
	 * elemento concatenante tra i vari filtri
	 */
	abstract function getOp();
}
?>
