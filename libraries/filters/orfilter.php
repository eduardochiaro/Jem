<?php

/**
 * Filtro che concatena più filtri in input al costruttore
 * mediante operatore logico OR
 */
class OrFilter extends ConcatFilter {
 		
	/**
	 * Metodo che ritorna l'operatore OR 
	 */
	function getOp() {
		return "OR";		
	}
}
?>
