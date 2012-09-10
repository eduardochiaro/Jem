<?php

/**
 * Filtro che concatena pi� filtri in input al costruttore
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
