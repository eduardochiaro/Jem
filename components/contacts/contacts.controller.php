<?php
/**
 * @title			contacts
 */
 



class contactsController extends Controller{
	var $_status = array("non contattato","contattato");
	function execView(){
		$workspace = $this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");

		// Costruisco filtro ricerca
		$usefilter = $workspace -> getGet("s-title");
		
		if($usefilter) {
			$usefilter = new LikeFilter("parameters", $workspace -> getGet("s-title"));
		} else {
			$usefilter = new TrueFilter();
		}
		
		$replyfilter = $workspace -> getGet("s-reply");
		
		if($replyfilter) {
			$replyfilter = new LikeFilter("reply", $workspace -> getGet("s-reply"),_LIKE_STRICT);
		} else {
			$replyfilter = new TrueFilter();
		}
		
		$statusfilter = $workspace -> getGet("s-status");
		
		if($statusfilter) {
			$statusfilter = new LikeFilter("status", $workspace -> getGet("s-status"),_LIKE_STRICT);
		} else {
			$statusfilter = new TrueFilter();
		}
		
		$filter = new AndFilter($usefilter,$replyfilter,$statusfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		$model = new Contact($workspace);
		$pagination = $model->getPagination("*", $filter->toSQL(), "created DESC");
		
		//get categorie
		$cat = new Reply($workspace);
		
		$workspace->set("replies", $cat->getAll("*"));
		$workspace->set("status", $this->_status);
		
		Utils::getTemplate($workspace,$module,$task,$pagination);
	}
	
	function execReplies(){
		$workspace = $this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");

		// Costruisco filtro ricerca
		$usefilter = $workspace -> getGet("s-title");
		
		if($usefilter) {
			$usefilter = new LikeFilter("t.text_1", $workspace -> getGet("s-title"));
		} else {
			$usefilter = new TrueFilter();
		}
		
		$filter = new AndFilter($usefilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		$model = new Reply($workspace);
		$pagination = $model->getPagination("*", $filter->toSQL());
		
		Utils::getTemplate($workspace,$module,"view-reply",$pagination);
	}
	

	function execNewReply(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");

		$model=new Reply($workspace);
		
		Utils::getTemplate($workspace,$module,"open-reply",$model);
		
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

		
		$filter = new AndFilter($idfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		
		$model=new Contact($workspace);
		if($id){
			$model->getSingle("*",$filter->toSQL());
		}
		$model->bind($post);

		
		Utils::getTemplate($workspace,$module,"open",$model);
		
	}
	
	function execOpenReply(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");

		
		$id = $workspace->getRequest("id");
		
		$row=null;
		$post=$workspace->getPost();
		
		$idfilter = new LikeFilter("id", $id,_LIKE_STRICT);

		
		$filter = new AndFilter($idfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		
		$model=new Reply($workspace);
		if($id){
			$model->getSingle("*",$filter->toSQL());
		}
		$model->bind($post);

		
		Utils::getTemplate($workspace,$module,"open-reply",$model);
		
	}
	
	
	function execSaveReply(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		$translations = $workspace->getTranslation();
		
		$my = $workspace->loadHandler("access");
		
	
		$post=$workspace->getPost();
		
		
		$model=new Reply($workspace);
		$model->getSingle("*",array("id"=>$post['id']));

		
		$post=$workspace->getPost();
		
		if($post){
			$model->save($post, true);
		}

		$validate = new CheckForm($workspace);
		$valido=$validate->validate($model);

		if(!$valido){
			$this->execOpenReply();
			exit();
		}
		
		if($model->id > 0){
			$update=false;
			$model->update();
			
			$workspace->addInfoMessage(sprintf(_LN_INFO_UPDATE_ITEM,$model->title));
		}else{
			$model->insert();
			$workspace->addInfoMessage(sprintf(_LN_INFO_ADD_ITEM,$model->title));
		}
		
		Utils::redirectOption($module,"replies");
	}
	
	function execDeleteReply(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		$db=$workspace->getDatabase();
		
		$id = $workspace->getRequest("id");
		
		$model=new Reply($workspace);
		$model->getSingle("*",array("id"=>$id));

		$model->delete();
		
		
		$workspace->addInfoMessage(sprintf(_LN_INFO_DELETE_ITEM,$model->title));
		
		Utils::redirectOption($module);
	}

	
	function execValidateReply(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		
		$id = $workspace->getRequest("id");
		$validate = $workspace->getRequest("validate");
		
		if($id>0){
			$model=new Reply($workspace);
			$model->getSingle("*",array("id"=>$id));
			$model->publish=$validate;
		

			$model->update(false,false);
			$workspace->addInfoMessage(sprintf(_LN_INFO_UPDATE_ITEM,$model->title));
		}
		
		Utils::redirectOption($module,"replies");
		
	}
	
	
	function execSave(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		$translations = $workspace->getTranslation();
		
		$my = $workspace->loadHandler("access");
		
	
		$post=$workspace->getPost();
		
		
		$model=new Contact($workspace);
		$model->getSingle("*",array("id"=>$post['id']));

		
		$post=$workspace->getPost();
		
		if($post){
			$model->save($post, true);
		}

		$validate = new CheckForm($workspace);
		$valido=$validate->validate($model);

		if(!$valido){
			$this->execOpenReply();
			exit();
		}
		
		if($model->id > 0){
			$update=false;
			$model->update();
			
			$workspace->addInfoMessage(sprintf(_LN_INFO_UPDATE_ITEM,$model->getParameter("name")." ".$model->getParameter("surname")));
		}else{
			$model->insert();
			$workspace->addInfoMessage(sprintf(_LN_INFO_ADD_ITEM,$model->getParameter("name")." ".$model->getParameter("surname")));
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
		
		$model=new Contact($workspace);
		$model->getSingle("*",array("id"=>$id));

		$model->delete();
		
		
		$workspace->addInfoMessage(sprintf(_LN_INFO_DELETE_ITEM,$model->getParameter("name")." ".$model->getParameter("surname")));
		
		Utils::redirectOption($module);
	}


	
	function execStatus(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		
		$id = $workspace->getRequest("id");
		$status = $workspace->getRequest("status");
		
		if($id>0){
			$model=new Contact($workspace);
			$model->getSingle("*",array("id"=>$id));
			$model->status=$status;
		
			$model->update(false,false);
			$workspace->addInfoMessage(sprintf(_LN_INFO_UPDATE_ITEM,$model->getParameter("name")." ".$model->getParameter("surname")));
		}
		
		Utils::redirectOption($module);
		
	}



}
