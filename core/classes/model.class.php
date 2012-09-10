<?php
class Model {
	
	private $workspace;
	private $fields;
	private $tr_fields;
	private $table;

	function __construct($workspace){
		$this->setWorkspace($workspace);
	}

	function getWorkspace(){
		return $this->workspace;
	}
	
	function setWorkspace($workspace){
		$this->workspace=$workspace;
	}
	
	function getFields(){
		return $this->fields;
	}
	
	function setFields($fields){
		$this->fields=$fields;
	}
	
	function getTranslateFields(){
		return $this->tr_fields;
	}
	
	function haveTranslateFields(){
		if(!$this->tr_fields){
			return false;	
		}
		if(!is_array($this->tr_fields)){
			return false;
		}
		return true;
	}
	
	function setTranslateFields($tr_fields){
		$this->tr_fields=$tr_fields;
	}
	
	function getTable(){
		return $this->table;
	}
	
	function setTable($table){
		$this->table=$table;
	}

	function getSingle($search="*",$where=false,$orderby=false){
		$workspace=$this->getWorkspace();
		$db=$workspace->getDatabase();
		$translations = $workspace->getTranslation();
		
		$table = $this->getTable();

		if($table){
			$select = $db->select()->from($table,$search);

			if($where = $this->setWhere($where)){
				$select->where($where);
				if($this->haveTranslateFields()){
					$select->joinLeft(array('t' => $translations->__get("TABLE_TRANSLATIONS")), $table.'.id = t.reference AND t.model =\''.$this->getTable().'\'', "language");
				}
			}
			
			if($orderby){
				$select->order($orderby);
			}
			$select->limit(1);

			$row = $db->fetchRow($select); 
			
			$select->reset();
			$this->bind($row);
			if($this->haveTranslateFields()){
				$row = $this->loadTranslationSingle($this, $row['language']);
			}
		}
		return false;
	}
	
	function getAll($search="*",$where=false,$orderby=false,$limits=false){
		$workspace=$this->getWorkspace();
		$db=$workspace->getDatabase();
		$translations = $workspace->getTranslation();
		
		$table = $this->getTable();
		if($table){
			$select = $db->select()->from($table,$search);
			
			if($where = $this->setWhere($where)){
				$select->where($where);
				if($this->haveTranslateFields()){
					$select->joinLeft(array('t' => $translations->__get("TABLE_TRANSLATIONS")), $table.'.id = t.reference AND t.model =\''.$this->getTable().'\'', "language");
				}
			}
			if($orderby){
				$select->order($orderby);
			}
			$select->group($table.'.id');
			if($limits){
				$select->limit($limits);
			}
			
			$smt = $db->query($select); 
			
			$rows = $smt->fetchAll();
			$select->reset();
			
			$rows = $this->bindAll($rows);
			
			return $rows;
		}
		return false;
	}
	
	function getPagination($search="*",$where=false, $orderby=false, $per_page= _LIMIT_PAGINATIONS_, $max_page= _PAGES_PAGINATIONS_){
		$workspace=$this->getWorkspace();
		$db=$workspace->getDatabase();
		$translations = $workspace->getTranslation();
		
		$table = $this->getTable();
		if($table){
			$select = $db->select()->from($table, $search);

			if($where = $this->setWhere($where)){
				$select->where($where);
				if($this->haveTranslateFields()){
					$select->joinLeft(array('t' => $translations->__get("TABLE_TRANSLATIONS")), $table.'.id = t.reference AND t.model =\''.$this->getTable().'\'', "language");
				}
			}
			if($orderby){
				$select->order($orderby);
			}
			$select->group($table.'.id');

		}
		
		$class_name = get_class($this);
		
		$query = $select->__toString();
		
		$pagination = new Paginations($query, $workspace, $class_name, $per_page , $max_page);
		
		return $pagination;
	}	
	
	function insert($noupdate = false){
		$workspace=$this->getWorkspace();
		$db=$workspace->getDatabase();
		
		$table = $this->getTable();
		$model = $this;
		
		
		$noupdate[]="id";
		$noupdate[]="workspace";
		$noupdate[]="fields";
		$noupdate[]="tr_fields";
		$noupdate[]="table";
		
		if(is_array($this->getTranslateFields())){
			foreach($this->getTranslateFields() as $key => $value){
				$noupdate[]=$key;
			}
		}
		if($noupdate){
			$noupdate=array_flip($noupdate);
			$model=get_object_vars($model);
			$model=array_diff_key($model,$noupdate);
		}
		$db->insert($table, $model);
		$this->id = $db->lastInsertId();
		
		/*salva traduci*/
		if($this->haveTranslateFields()){
			$translations = $workspace->getTranslation();
			$post=$workspace->getPost();
			$translations->saveData($post,$table,$this->id);
		}
		
		return true;
	}
	
	function update($noupdate = false, $updatetranslation = true){
		$workspace=$this->getWorkspace();
		$db=$workspace->getDatabase();
		
		$table = $this->getTable();
		$model = $this;
		
		$noupdate[]="id";
		$noupdate[]="workspace";
		$noupdate[]="fields";
		$noupdate[]="tr_fields";
		$noupdate[]="table";
		if(is_array($this->getTranslateFields())){
			foreach($this->getTranslateFields() as $key => $value){
				$noupdate[]=$key;
			}
		}
		
		if($noupdate){
			$noupdate=array_flip($noupdate);
			$model=get_object_vars($model);
			$model=array_diff_key($model,$noupdate);
		}
		
		$id = $this->id;
		
		$where = $db->quoteInto('id = ?', $id);

		$response = $db->update($table, $model, $where);

		if($updatetranslation && $this->haveTranslateFields()){
			/*salva traduci*/
			$translations = $workspace->getTranslation();
			$post=$workspace->getPost();
			$translations->saveData($post,$table,$this->id);
		}
		
		return true;
		
	}
	
	function delete(){
		$workspace=$this->getWorkspace();
		$db=$workspace->getDatabase();
		
		$table = $this->getTable();
		$model = $this;
		
		$id = $this->id;
		
		$where = $db->quoteInto('id = ?', $id);
		$remove = $db->delete($table, $where);
		
		
		/*cancella traduci*/
		if($this->haveTranslateFields()){
			$translations = $workspace->getTranslation();
			$translations->deleteData($table,$this->id);
		}
		
		return true;
		
	}
	
	function setWhere($where){
		if($where){
			if(is_array($where)){
				$nwhere="1=1";
				foreach($where as $key => $value){
					$nwhere.=" AND `".$this->getTable()."`.`".$key."`='".trim($value)."'";
				}
				$where=$nwhere;
			}
			return $where;
		}else{
			return false;
		}
	}
	function loadTranslation($rows, $actual = true){
		$workspace=$this->getWorkspace();
		$translations = $workspace->getTranslation();
		
		
		foreach($rows as $row){

			$translations->getData($row, $actual);
		}
	}
	function loadTranslationSingle($row, $actual = true){
		$workspace=$this->getWorkspace();
		$translations = $workspace->getTranslation();
		
		
		$result =  $translations->getData($row, $actual);

		Model::bindObject($row, $result, false);
		//$row->bind($result);
		//return $row; 
	}
	
	function bindAll($rows){
		$workspace=$this->getWorkspace();
		$ret_new = array();

		$class_name = get_class($this);
		// Ciclo su elements
		foreach($rows as $row){
			$entity = new $class_name($workspace);
			$entity -> bind($row);
			if($entity->haveTranslateFields()){
				$row = $entity->loadTranslationSingle($this, $row['language']);
			}
			$ret_new[] = $entity;
		}
		
		return $ret_new;
	}
	function bind($array_info, $override_blank=false) {
		Model::bindObject($this, $array_info, $override_blank);
		Model::loadTranslationSingle($this, true);
	}
	
	/**
	 * Inizializza l'oggetto ricercando nell'array associativo 
	 * in input il valore assegnato alle chiavi che 
	 * identificano gli attributi dell'oggetto stesso
	 */
	function bindObject($object, $array_info, $override_blank=false) {
		
		// Se info nullo
		if(!isset($array_info)) {
			
			// Esco
			return;
		}
		
		// Forzo array info ad array
		if(is_object($array_info)) {
			$array_info = get_object_vars($array_info);
		}
		
		// Forzo chiavi array info tutte minuscole
		if(is_array($array_info)){
			$array_info = array_change_key_case($array_info, CASE_LOWER);
		}
		// Catturo var dell'oggetto
		$fields = get_object_vars($object);
		
		// Ciclo su vars
		foreach ($fields as $v => $f) {

			// Assegno valore da array
			if (isset ($array_info[strtolower($v)])) {
				
				if(is_array($array_info[strtolower($v)]) && (!empty($array_info[strtolower($v)]) || $override_blank)) {
					$object-> $v = $array_info[strtolower($v)];

				} else if($array_info[strtolower($v)]!="" || $override_blank) {
					$object-> $v = $array_info[strtolower($v)];
				}
			}
		}
		
		//if($object->getTable() == "mailing") Log::error($object);
	}
	
	function equals($object) {
	 	
	 	// Entity è un oggetto
	 	if(!is_object($object)) {
	 		return false;
	 	}
	 	
	 	// Entity è un oggetto di tipo Models
	 	if(!($object instanceof Models)) {
	 		return false;
	 	}
	 	
	 	// Se stessa unità di memoria
	 	if($this === $object) {
	 		return true;
	 	}

	 	// Uguali se hanno lo stesso id
	 	return $this -> id == $object -> id;
	}
	
	function entityIndex($ago, $pagliaio) {
	 	
	 	// Check array
	 	if(!$pagliaio) {
	 		return false;
	 	}
	 	
	 	// Check Entity
	 	if(!$ago) {
	 		return false;
	 	}
	 	
	 	// Ciclo su array
	 	foreach($pagliaio as $i => $value) {
	 		if($ago -> equals($value)) {
	 			
	 			// Trovato
	 			return $i;
	 		}
	 	}
	 	
	 	// Non trovato
	 	return false;
	}
}
?>