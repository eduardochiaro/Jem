<?php
/**
 * @title ACCESS
 * @description modulo di accesso amministrativo
 * @task login|logout|access
 * @required true
 */

class accessController extends Controller{
	
	function execView(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");

		// Costruisco filtro ricerca
		$usefilter = $workspace -> getGet("s-username");
		
		if($usefilter) {
			$usefilter = new LikeFilter("username", $workspace -> getGet("s-username"));
		} else {
			$usefilter = new TrueFilter();
		}
		
		if(!$my->root){
			$notfilter = new NotFilter(new LikeFilter("root", "1"));
		}else {
			$notfilter = new TrueFilter();
		}
		
		$filter = new AndFilter($usefilter,$notfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		$model = new Access($workspace);
		$pagination = $model->getPagination("*", $filter->toSQL());
		
		
		Utils::getTemplate($workspace,$module,$task,$pagination);
	}
	
	
	function execNew(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");

		
		$model=new Access($workspace);
		
		
		$_tags = array();
		$required = FileSystem::files(_COMPONENTS_PATH, "\.xml$", true, true);
		if(is_array($required)){
			foreach ($required as $filename) {
				$ls = simplexml_load_file($filename)->config;
				$tags[] = $ls;
			}
		}
		foreach($tags as $tag){
			$_tags[(string)$tag->title] = $tag;
		}
		$workspace->set("componentsList",$_tags);

		$task=new Config($workspace);
		$task->getSingle("*",array("id"=>1));	
		$workspace->set('controllers', $task->getComponents());

		Utils::getTemplate($workspace,$module,"open",$model);
		
	}
	
	function execOpen(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");

		
		$id = $workspace->getRequest("id");
		
		$row=null;
		$post=$workspace->getPost();
		
		
		
		$idfilter = new LikeFilter("id", $id,_LIKE_STRICT);
		
		if(!$my->root){
			$notfilter = new NotFilter(new LikeFilter("root", "1",_LIKE_STRICT));
		}else {
			$notfilter = new TrueFilter();
		}
		
		$filter = new AndFilter($idfilter,$notfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		
		$model=new Access($workspace);
		if($id){
			$model->getSingle("*",$filter->toSQL());
		}
		$model->bind($post);
		
		$_tags = array();
		$required = FileSystem::files(_COMPONENTS_PATH, "\.xml$", true, true);
		if(is_array($required)){
			foreach ($required as $filename) {
				$ls = simplexml_load_file($filename)->config;
				$tags[] = $ls;
			}
		}
		foreach($tags as $tag){
			$_tags[(string)$tag->title] = $tag;
		}
		$workspace->set("componentsList",$_tags);

		$task=new Config($workspace);
		$task->getSingle("*",array("id"=>1));	
		$workspace->set('controllers', $task->getComponents());
		
		Utils::getTemplate($workspace,$module,"open",$model);
		
	}
	
	
	function execSave(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		$db=$workspace->getDatabase();
		
		$post=$workspace->getPost();
		if(!$post['task']){
			$post['task']=array("");
		}
		
		if($post){
			$model=new Access($workspace);
			$model->getSingle("*",array("id"=>$post['id']));
			$model->save($post, false);
		}

		$validate	= new CheckForm($workspace);
		$valido		= $validate->validate($model);
		
		if($post['id']>0){
			if(($post['password'] != $post['conf-password'])){
				$valido = false;
				$workspace->addErrorMessage(sprintf(_LN_ERROR_CONFIRM,"password"));
			}
		}else{
			if($post['password'] ==""){
				$workspace->addErrorMessage(sprintf(_LN_ERROR_EMPTY,"password"));
			}
		}
		
		if(!$valido){
			$this->execOpen();
			exit();
		}
		
		if($model->id > 0){
			$update=false;
			if($post['password'] ==""){ $update=array("password");}
			$model->update($update);
			$workspace->addInfoMessage(sprintf(_LN_INFO_UPDATE_ITEM,$model->username));
		}else{
			$model->insert();
			$workspace->addInfoMessage(sprintf(_LN_INFO_ADD_ITEM,$model->username));
		}
		
		Utils::redirectOption($module);
		
		
	}
	
	function execDelete(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		$db=$workspace->getDatabase();
		
		$id = $workspace->getRequest("id");
		
		$model=new Access($workspace);
		$model->getSingle("*",array("id"=>$id));
		$model->delete();
		
		$workspace->addInfoMessage(sprintf(_LN_INFO_DELETE_ITEM,$model->username));
		
		Utils::redirectOption($module);
	}
	
	function execLogin(){
		$workspace = $this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		
		Utils::getTemplate($workspace,$module,$task,false,false);
		
	}
	
	function execLogout(){
		$workspace = $this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$workspace->addToWorkspace("administrator",0);
		
		$workspace->addInfoMessage(sprintf("logout effettuato",$module,$task));
		Utils::redirectOption("access","login");
		
		
		Utils::getTemplate($workspace,$module,$task,false,false);
		
	}
	
	function execAccess(){		
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		
		$fields=$workspace->getPost();
		
		
		$model=new Access($workspace);
		$model->getSingle(array("password","salt","id"),array("username"=>$fields['username']));

		if(count($model) > 0){	
			if(md5($fields["password"].":".$model->salt) == $model->password){
				
				$workspace->addToWorkspace("administrator",$model->id);
				

				Utils::redirectOption("index");
			}else{
				$workspace->addErrorMessage(sprintf("username o password errata",$module,$task));
				Utils::redirectOption("access","login");
			}
		}
	}
}
