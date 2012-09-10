<?php
/**
 * Classe di util per controllo campi
 */
class CheckForm {

	var $workspace;
	
	function CheckForm($workspace){
		$this->setWorkspace($workspace);
	}

	function getWorkspace(){
		return $this->workspace;
	}
	
	function setWorkspace($workspace){
		$this->workspace=$workspace;
	}

	function validate($model){
	
		$workspace=$this->getWorkspace();
		$fields=$model->getFields();
		$return=true;
		foreach($fields as $key => $subobj){

			list($check,$type,$length,$empty)=$subobj;
			$fun="check".ucfirst($type);
			$oggetto=$model->$key;
			
			if(is_numeric($length) && $length < strlen($oggetto) && $empty){
				$workspace->addErrorMessage(sprintf(_LN_ERROR_LENGTH,$key,$length));
				$workspace->addFormError($key);
				$return=false;
			}
			
			if($empty && $oggetto == ""){
				$workspace->addErrorMessage(sprintf(_LN_ERROR_EMPTY,$key));
				$workspace->addFormError($key);
				$return=false;
			}
			if(is_callable(array($this,$fun))){
				if($check && !$this->$fun($oggetto)){
					switch($type){
						case "email":
							$workspace->addErrorMessage(sprintf(_LN_ERROR_EMAIL,$key));
							$workspace->addFormError($key);
							break;
						case "int":
							$workspace->addErrorMessage(sprintf(_LN_ERROR_NUMERIC,$key));
							$workspace->addFormError($key);
							break;
						case "dateValid":
							$workspace->addErrorMessage(sprintf(_LN_ERROR_DATE,$key));
							$workspace->addFormError($key);
							break;
					}
					
					
					$return=false;
				}
			}
		}
		return $return;
	}
	

	/**
	 * Restituisce false se l'input non è una stringa formato yyyy-MM-dd
	 * sintatticamente corretta per la rappresentazione di un data 
	 */
	function checkDateValid($dateTime) {
		$s1 = "/^(\d{4})-(\d{1})-(\d{1})$/";
		$s2 = "/^(\d{4})-(\d{1})-(\d{2})$/";
		$s3 = "/^(\d{4})-(\d{2})-(\d{1})$/";
		$s4 = "/^(\d{4})-(\d{2})-(\d{2})$/";
		
	    if (
	    	preg_match($s1, $dateTime, $matches) || 
	    	preg_match($s2, $dateTime, $matches) || 
	    	preg_match($s3, $dateTime, $matches) || 
	    	preg_match($s4, $dateTime, $matches)
	    	) {
	        if (checkdate($matches[2], $matches[3], $matches[1])) {
	            return true;
	        }
	    }
	
	    return false;
	}

	/**
	 * Restituisce false se l'input mon è una email sintatticamente corretta.
	 */
	function checkEmail($email) {
		if (ereg("^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-\.]?[0-9a-zA-Z])*\\.[a-zA-Z]{2,3}$", $email)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Restituisce false se l'input non è un valore numerico sintatticamente corretta.
	 */
	function checkInt($number) {
		if (eregi("^[+-]?[0-9]*[\.]?[0-9]+$", $number)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Restituisce false se l'input non è una stringa di soli caratteri validi per le userid.
	 */
	function checkText($value) {
		if($value){
			return true;
		}else{
			return false;	
		}
	}

	/**
	 * Restituisce false se l'input non e pieno.
	 */
	function checkCheckbox($value = false) {
		if($value){
			return true;
		}else{
			return false;	
		}
	}

	/**
	 * Restituisce false se l'input non è una stringa di soli caratteri validi per le userid.
	 */
	function checkCharacters($value) {
		if (eregi("^[A-Za-z0-9\\._-]+$", $value)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Restituisce false se l'input non è una partita iva corretta sintatticamente.
	 */
	function checkVatNumber($pi) {

		// Campo non vuoto
		if ($pi == '') {
			return false;
		}

		// Lunghezza 11 caratteri
		if (strlen($pi) != 11) {
			return false;
		}

		// Colo caratteri numerici
		if (!ereg("^[0-9]+$", $pi)) {
			return false;
		}

		// Algoritmo di controllo
		$s = 0;
		for ($i = 0; $i <= 9; $i += 2) {
			$s += ord($pi[$i]) - ord('0');
		}

		for ($i = 1; $i <= 9; $i += 2) {
			$c = 2 * (ord($pi[$i]) - ord('0'));

			if ($c > 9) {
				$c = $c -9;
			}

			$s += $c;
		}

		if ((10 - $s % 10) % 10 != ord($pi[10]) - ord('0')) {
			return false;
		}

		// Partita iva corretta	 
		return true;
	}

	/**
	 * Restituisce false se l'input non è un codice fiscale corretto sintatticamente.
	 */
	function checkFiscalCode($cf) {

		// Coversione a tutto maiuscolo
		$cf = strtoupper($cf);

		// Campo non vuoto
		if ($cf == '') {
			return false;
		}

		// Lunghezza 16 caratteri
		if (strlen($cf) != 16) {
			return false;
		}

		// Colo caratteri alfanumerici
		if (!ereg("^[A-Z0-9]+$", $cf)) {
			return false;
		}

		// Algoritmo di controllo
		$s = 0;
		for ($i = 1; $i <= 13; $i += 2) {
			$c = $cf[$i];
			if ('0' <= $c && $c <= '9') {
				$s += ord($c) - ord('0');
			} else {
				$s += ord($c) - ord('A');
			}
		}

		for ($i = 0; $i <= 14; $i += 2) {
			$c = $cf[$i];
			switch ($c) {
				case '0' :
					$s += 1;
					break;

				case '1' :
					$s += 0;
					break;

				case '2' :
					$s += 5;
					break;

				case '3' :
					$s += 7;
					break;

				case '4' :
					$s += 9;
					break;

				case '5' :
					$s += 13;
					break;

				case '6' :
					$s += 15;
					break;

				case '7' :
					$s += 17;
					break;

				case '8' :
					$s += 19;
					break;

				case '9' :
					$s += 21;
					break;

				case 'A' :
					$s += 1;
					break;

				case 'B' :
					$s += 0;
					break;

				case 'C' :
					$s += 5;
					break;

				case 'D' :
					$s += 7;
					break;

				case 'E' :
					$s += 9;
					break;

				case 'F' :
					$s += 13;
					break;

				case 'G' :
					$s += 15;
					break;

				case 'H' :
					$s += 17;
					break;

				case 'I' :
					$s += 19;
					break;

				case 'J' :
					$s += 21;
					break;

				case 'K' :
					$s += 2;
					break;

				case 'L' :
					$s += 4;
					break;

				case 'M' :
					$s += 18;
					break;

				case 'N' :
					$s += 20;
					break;

				case 'O' :
					$s += 11;
					break;

				case 'P' :
					$s += 3;
					break;

				case 'Q' :
					$s += 6;
					break;

				case 'R' :
					$s += 8;
					break;

				case 'S' :
					$s += 12;
					break;

				case 'T' :
					$s += 14;
					break;

				case 'U' :
					$s += 16;
					break;

				case 'V' :
					$s += 10;
					break;

				case 'W' :
					$s += 22;
					break;

				case 'X' :
					$s += 25;
					break;

				case 'Y' :
					$s += 24;
					break;

				case 'Z' :
					$s += 23;
					break;
			}
		}

		if (chr($s % 26 + ord('A')) != $cf[15]) {
			return false;
		}

		return true;
	}
}