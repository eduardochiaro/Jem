<?php

/*
 * Created on 11/mar/08
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class Paginations {
	var $elements;
	var $links;
	
	/**
	 * @param string $query Query di selezione
	 * @param string $workspace Workspace 
	 * @param int $per_page Numero di elementi per pagina. Default _LIMIT_PAGINATIONS_
	 * @param string $class_name Classe estensione di Entity. 
	 * Se NULL la proprietà elements conterrà oggetti resultset, altrimenti saranno instanze della classe in input. Default _Nu
	 */
	function Paginations($query=false, $workspace, $class_name = NULL, $per_page= _LIMIT_PAGINATIONS_, $max_page= _PAGES_PAGINATIONS_){
		$db=$workspace->getDatabase();
		$translations = $workspace->getTranslation();
		
		$entity = new $class_name($workspace);

		$count = $this->countObjects($query, $workspace);

		
		$limit=$this->searchLimit($count, $workspace, $per_page, $max_page);
		$link=$this->paginazione($count, $workspace, $per_page, $max_page);
		

		$query = preg_replace("/LIMIT.*/i","",$query);
		$smt = $db->query($query." LIMIT ".$limit.",".$per_page); 

		$rows = $smt->fetchAll();
			
		$rows = $entity->bindAll($rows);

		
		$this->setElements($rows);
		$this->setLinks($link);

	}
	
	function countObjects($query,$workspace){
		$db=$workspace->getDatabase();

		$smt = $db->query($query); 
		$rows = $smt->fetchAll();


		return count($rows);
	}
	 
	/*
	 * paginazione
	 */
	function paginazione($tot_records, $workspace, $per_page, $max_page) {
		
		// Catturo pagina richieste
		$page = $this->getRequestPage($workspace);
				
		$sostituto=explode(",",_QUERYSTRING_DELETE);

		$query=$workspace->getServer('QUERY_STRING');
		
		$querystring=Utils::clearFromQueryString($query,$sostituto);
		

		if ($tot_records == 0) {
			$paginazione = false;
		}

		$tot_pages = ceil($tot_records / $per_page);
		if($page > $tot_pages) {
			$page = 1; 
		}

		if ($page == "last") {
			$page = $tot_pages;
		}

		$indice_inizio = $page - ($max_page / 2);
		if ($indice_inizio > ($tot_pages - $max_page)) {
			$indice_inizio = $tot_pages - $max_page +1;
		}
		if ($indice_inizio <= 0) {
			$indice_inizio = 1;
		}
		$indice_fine = $indice_inizio + $max_page -1;
		if ($indice_fine > $tot_pages) {
			$indice_fine = $tot_pages;
		}

		$current_page = (!$page) ? 1 : (int) $page;

		$primo = ($current_page -1) * $per_page;
		

		$paginazione = null;
		if ($tot_pages > 1) {
			$paginazione = array();
		}
		
		$querystring.=($querystring)?"&":"";
		
		if ($page > 1) {
			$paginazione['first']= Utils::siteOptions("backend") . "?".$querystring."page=1";
		}
		if ($indice_inizio <> 1) {
			$paginazione['back']= Utils::siteOptions("backend"). "?".$querystring."page=". ($indice_inizio -1) ;
		}
		for ($i = 1; $i <= $tot_pages; $i++) {
			if ($i == $current_page) {
				if ($tot_pages != 1) {
					$paginazione['pages'][$i-1]['url']="";
					$paginazione['pages'][$i-1]['page']=$i;
				}
			} else {
				if ($i > ($indice_inizio -1) AND $i < ($indice_fine +1)) {
					$paginazione['pages'][$i-1]['url']= Utils::siteOptions("backend") . "?".$querystring."page=".$i;
					$paginazione['pages'][$i-1]['page']=$i;
				}
			}
		}
		if ($indice_fine < ($tot_pages -1)) {
			$paginazione['next']= Utils::siteOptions("backend")  . "?".$querystring."&page=". ($indice_inizio +1) ;
		}
		if ($page < $tot_pages AND $tot_pages != 1) {
			$paginazione['last']= Utils::siteOptions("backend") . "?".$querystring."page=". $tot_pages ;
		}
		
		$paginazione['actualpage']=$current_page;
		$paginazione['maxpage']=$tot_pages;
		$paginazione['generalurl']= Utils::siteOptions("backend") . "?".$querystring;
		
		return $paginazione;
	}
	/*
	 * limite paginazione
	 */
	function searchLimit($tot_records,$workspace,$per_page, $max_page ) {

		$page = $this->getRequestPage($workspace);

		$tot_pages = ceil($tot_records / $per_page);
		if($page > $tot_pages) {
			$page = 1; 
		}

		if ($page == "last") {
			$page = $tot_pages;
		}

		$indice_inizio = $page - ($max_page / 2);
		if ($indice_inizio > ($tot_pages - $max_page)) {
			$indice_inizio = $tot_pages - $max_page +1;
		}
		if ($indice_inizio <= 0) {
			$indice_inizio = 1;
		}
		$indice_fine = $indice_inizio + $max_page -1;
		if ($indice_fine > $tot_pages) {
			$indice_fine = $tot_pages;
		}

		$current_page = (!$page) ? 1 : (int) $page;

		$primo = ($current_page -1) * $per_page;

		return $primo;
	}
	
	/**
	 * Ritorna la pagina richiesta
	 */
	function getRequestPage($workspace) {
		$page = $workspace->getRequest("page");
		return ($page) ? $page : 1; 
	}
	
	function setElements($elements){
		$this->elements=$elements;	
	}
	
	function getElements(){
		return $this->elements;
	}
	
	function setLinks($links){
		$this->links=$links;	
	}
	
	function getLinks(){
		return $this->links;
	}
}
?>
