<?php
/**
 * @title			CONFIG
 * @description		modulo per la configurazione del sistema
 * @task			view:visualizza|open:apri|save:salva|active:attiva
 * @required true
 */


class configController extends Controller{
	
	var $_tags;
	
	function execView(){
		$workspace = $this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		$model=new Config($workspace);
		$model->getSingle("*",array("id"=>1));
		
		
		$post=$workspace->getRequest();
		if($post){
			$model->bind($post, true);
		}
		
		Utils::getTemplate($workspace,$module,"view",$model);
	}
	function execSave(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		$my = $workspace->loadHandler("access");
		
		
		$post=$workspace->getRequest();
		if($post){
			$model=new Config($workspace);
			$model->getSingle("*",array("id"=>1));
	
			$model->bind($post, true);
		}

		$validate = new CheckForm($workspace);
		$valido=$validate->validate($model);
		
		if(!$valido){
			$this->execView();
			exit();
		}
		
		if($model->update(array("components"))){
			$workspace->addInfoMessage(sprintf("Modifica Eseguita",$module,$task));
			Utils::redirectOption($module);
		}
		
		
	}
	
	function execComponents(){
		$workspace = $this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		//$my->isLogged();
		
		$required = FileSystem::files(_COMPONENTS_PATH, "\.xml$", true, true);
		if(is_array($required)){
			foreach ($required as $filename) {
				$ls = simplexml_load_file($filename)->config;
				$tags[] = $ls;
			}
		}
		foreach($tags as $tag){
			$this->_tags[(string)$tag->title] = $tag;
		}
		
		
		$model=new Config($workspace);
		$model->getSingle("*",array("id"=>1));
		
		
		$workspace->set("componentsList",$model->getComponents());

		
		Utils::getTemplate($workspace,$module,"components",$this->_tags);
	}
	function execCompSave(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		$my = $workspace->loadHandler("access");
		
		
		$post=$workspace->getRequest();
		
		$model=new Config($workspace);
		$model->getSingle("*",array("id"=>1));
		
		if($post["add"]==1){
			$model->addToComponents($post["id"]);

		}else{
			$model->removeToComponents($post["id"]);
		}

		
		if($model->update()){
			$workspace->addInfoMessage(sprintf(_LN_INFO_UPDATE_ITEM,$post["id"]));
		}else{
			$workspace->addErrorMessage(sprintf(_LN_ERROR_DB,$workspace->getDatabaseError()));
		}
		Utils::redirectOption($module,"components");
		
	}
	function execBackup(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		$my = $workspace->loadHandler("access");
		
		$data_oggi = date("dmYHis");
		$file_sql = _FRAMEWORK_UPLOADS_PATH."/backup/backup_".$data_oggi.".sql";
		$file_zip = _FRAMEWORK_UPLOADS_PATH."/backup/backup_".$data_oggi.".zip";
		$down_zip = Utils::siteOptions("siteurl")."uploads/framework/backup/backup_".$data_oggi.".zip";
	  #####################################################################################################################
	  #  Set the parameters: hostname, databasename, dbuser and password(must have SELECT permission to the mysql DB)     #
	  #  Note that this produces a GZip compressed file. You should set the third parameter to false to get an SQL file   #
	  #  This will dump the database into the file located in "./file.sql.gz"                                             #
	  #####################################################################################################################

	  	$backup = new iam_backup(_DB_HOST, _DB_NAME, _DB_USER, _DB_PASSWORD, true, false, false, $file_sql);
	
	  	$backup->perform_backup();
	
	  #####################################################################################################################
	  #  Call the perform backup function  and that's it!!!                                                               #
	  #####################################################################################################################
	  
		$z = new EasyZIP;
		$z -> addFile($file_sql);
		
		$z -> zipFile($file_zip);
	
	
		if (is_file($file_sql)) unlink($file_sql);
		
		Utils::getTemplate($workspace,$module,$task,$down_zip);
		
	}
}
