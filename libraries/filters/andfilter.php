<?php
require_once (_LIBRARIES_PATH."/filters/concatfilter.php");

/**
 * Filtro che concatena più filtri in input al costruttore
 * mediante operatore logico AND
 */
class AndFilter extends ConcatFilter {
	
	/**
	 * Metodo che ritorna l'operatore AND 
	 */
	function getOp() {
		return "AND";		
	}
}
?>
