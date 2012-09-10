<?php
class UserMailing extends Model{
	var $id;
	var $name;
	var $surname;
	var $email;
	var $category;
	var $parameters;
	var $created;
	var $lastmodified;
	var $lastuser;
	var $publish;
	var $token;
	
	function __construct($workspace){
		parent::setWorkspace($workspace);
		parent::setTable("mailing_users");
		
		//array (validare,tipo,lunghezza,deve essere pieno)
		$this->setFields(array(
			'id'=>array(0,'int',8,false),
			'name'=>array(1,'text',220,true),
			'surname'=>array(1,'text',220,true),
			'email'=>array(1,'text',220,true),
			'category'=>array(0,'int',8,false),
			'parameters'=>array(1,'longtext','',false),
			'created'=>array(1,'datetime','',true),
			'lastmodified'=>array(1,'datetime','',true),
			'lastuser'=>array(1,'int',8,true),
			'publish'=>array(1,'int',8,false),
			'token'=>array(1,'text',220,true)				
		));
		$this->created = date("Y-m-d H:i:s");
		$this->publish = 0;
		$this->token = "";
	}
	
	function save($post,$over=false){		
		if(!$post['lastmodified']){
			$post['lastmodified'] = date("Y-m-d H:i:s");
		}
     
		if(!$post['pubdate']){
			$post['pubdate'] = date("Y-m-d");
		}else{
			$post['pubdate'] = Utils::parseDate($post['pubdate'],"%Y-%m-%d");
		}
		if(!$post['lastuser']){
			$workspace = $this->getWorkspace();
			$my = $workspace->loadHandler("access");
			$post['lastuser'] = $my->id;
		}
		if($post['parameters']){
			$post['parameters'] = serialize($post['parameters']);
		}
		
		parent::bind($post,$over);
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
				$ntab = new GroupUserMailing($workspace);
				$select->joinLeft(array('g' => $ntab->getTable()), $table.'.id = g.user', "group");
			}
			
			if($orderby){
				$select->order($orderby);
			}
			$select->limit(1);

			$row = $db->fetchRow($select); 
			$select->reset();
			$this->bind($row);
			$row = $this->loadTranslationSingle($this, $row['language']);
		}
		return false;
	}
	
	function getAll($search="*",$where=false,$orderby=false,$limits=false){
		$workspace=$this->getWorkspace();
		$db=$workspace->getDatabase();
		
		$table = $this->getTable();
		if($table){
			$select = $db->select()->from($table,$search);

			if($where = $this->setWhere($where)){
				$select->where($where);
				$ntab = new GroupUserMailing($workspace);
				$select->joinLeft(array('g' => $ntab->getTable()), $table.'.id = g.user', "group");
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
			$select = $db->select()->from($table,$search);

			if($where = $this->setWhere($where)){
				$select->where($where);
				$ntab = new GroupUserMailing($workspace);
				$select->joinLeft(array('g' => $ntab->getTable()), $table.'.id = g.user', "group");
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


	function getParameters(){
		$workspace=$this->getWorkspace();
		
		$parameters=unserialize($this->parameters);
		if(!is_array($parameters)){
			return false;
		}
		
		return $parameters;

	}

	function getParameter($key){
		$workspace=$this->getWorkspace();
		
		$parameters=unserialize($this->parameters);
		if(!is_array($parameters)){
			return false;
		}
		
		if($parameters[$key]){
			return $parameters[$key];
		}else{
			return false;
		}
	}
}