<?php
/**
 * @title			mailing
 */


class mailingController extends Controller{
	
	private $_categories = array(1=>"newsletter",2=>"mailinglist");
	
	function execView(){
		$workspace = $this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");

		$model = new Mailing($workspace);
		// Costruisco filtro ricerca
		$usefilter = $workspace -> getGet("s-title");
		
		if($usefilter) {
			$usefilter = new LikeFilter($model ->getTable().".title", $workspace -> getGet("s-title"));
		} else {
			$usefilter = new TrueFilter();
		}
		
		$modelfilter = $workspace -> getGet("s-category");
		
		if($modelfilter) {
			$modelfilter = new LikeFilter($model ->getTable().".type", $workspace -> getGet("s-category"), _LIKE_STRICT);
		} else {
			$modelfilter = new TrueFilter();
		}
		
		$filter = new AndFilter($usefilter,$modelfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		$pagination = $model->getPagination("*", $filter->toSQL(), "created DESC");
		
		//get categorie
		
		$workspace->set("categories", $this->_categories);
		
		
		$workspace->set("parameters", $this->getParameters());
		
		Utils::getTemplate($workspace,$module,$task,$pagination);
	}
	
	
	function execCsv(){
		$workspace = $this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		

		//get categorie
		$cat = new GroupMailing($workspace);
		$workspace->set("categories", $cat->getAll("*"));
		
		Utils::getTemplate($workspace,$module,$task);
	}
	
	function execLoadCsv(){
		$workspace = $this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$csv = Upload::uploadCSV('csv', $workspace);
		//Log::error($csv);
		
		$category = $workspace->getPost("category");
		
		
		foreach($csv as $item){
			if($item[2]){
				$user = new UserMailing($workspace);
				
				$usefilter = new LikeFilter("email", $item[2], _LIKE_STRICT);
				$filter = new AndFilter($usefilter);
				if(!$filter) {
					$filter = new TrueFilter();
				}
				$user->getSingle("*", $filter->toSQL());
				if(!$user->id){
					
					$nuser = new UserMailing($workspace);
					$nuser->name	= $item[0];
					$nuser->surname	= $item[1];
					$nuser->email	= $item[2];
					if($item[3] == ""){
						$nuser->publish	= 1;
					}
					
					$nuser->insert();	
					
					if($category > 0){
						$cat = new GroupUserMailing($workspace);
						$cat->group = $category;
						$cat->user = $nuser->id;
						$cat->insert();
					}
				}else{
					$workspace->addErrorMessage(sprintf(_LN_ERROR_UNIQUE_ELEMENT,$user->name." ".$user->surname));
				}
			}
		}
		
		Utils::redirectOption($module,"users");
	}
	
	
	function execUsers(){
		$workspace = $this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");

		$model = new UserMailing($workspace);
		// Costruisco filtro ricerca
		$usefilter = $workspace -> getGet("s-title");
		
		if($usefilter) {
			$usefilter_1 = new LikeFilter($model ->getTable().".name", $workspace -> getGet("s-title"));
			$usefilter_2 = new LikeFilter($model ->getTable().".surname", $workspace -> getGet("s-title"));
			$usefilter_2 = new LikeFilter($model ->getTable().".email", $workspace -> getGet("s-title"));
			$usefilter = new OrFilter($usefilter_1,$usefilter_2,$usefilter_2);
		} else {
			$usefilter = new TrueFilter();
		}
		
		$modelfilter = $workspace -> getGet("s-category");
		
		if($modelfilter) {
			$modelfilter = new LikeFilter("g.group", $workspace -> getGet("s-category"), _LIKE_STRICT);
		} else {
			$modelfilter = new TrueFilter();
		}
		
		$filter = new AndFilter($usefilter,$modelfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		$pagination = $model->getPagination("*", $filter->toSQL());
		
		//get categorie
		
		//get categorie
		$cat = new GroupMailing($workspace);
		$workspace->set("categories", $cat->getAll("*"));
		
		Utils::getTemplate($workspace,$module,$task,$pagination);
	}
	
	function execSenders(){
		$workspace = $this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");

		$model = new SenderMailing($workspace);
		// Costruisco filtro ricerca
		$usefilter = $workspace -> getGet("s-title");
		
		if($usefilter) {
			$usefilter = new LikeFilter($model ->getTable().".title", $workspace -> getGet("s-title"));
		} else {
			$usefilter = new TrueFilter();
		}
		
		$filter = new AndFilter($usefilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		$pagination = $model->getPagination("*", $filter->toSQL());
		
		Utils::getTemplate($workspace,$module,$task,$pagination);
	}
	
	function execGroups(){
		$workspace = $this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");

		$model = new groupMailing($workspace);
		// Costruisco filtro ricerca
		$usefilter = $workspace -> getGet("s-title");
		
		if($usefilter) {
			$usefilter = new LikeFilter($model ->getTable().".title", $workspace -> getGet("s-title"));
		} else {
			$usefilter = new TrueFilter();
		}
		
		$filter = new AndFilter($usefilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		$pagination = $model->getPagination("*", $filter->toSQL());
		
		Utils::getTemplate($workspace,$module,$task,$pagination);
	}
	
	function execTemplates(){
		$workspace = $this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");

		$model = new TemplateMailing($workspace);
		// Costruisco filtro ricerca
		$usefilter = $workspace -> getGet("s-title");
		
		if($usefilter) {
			$usefilter = new LikeFilter("t.text_1", $workspace -> getGet("s-title"));
		} else {
			$usefilter = new TrueFilter();
		}
		
		if($usefilter_r) {
			$usefilter_r = new LikeFilter("t.text_2", $workspace -> getGet("s-title"));
		} else {
			$usefilter_r = new TrueFilter();
		}
		
		$filter = new AndFilter($usefilter,$usefilter_r);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		$pagination = $model->getPagination("*", $filter->toSQL());
		
		Utils::getTemplate($workspace,$module,$task,$pagination);
	}
	
	function execGetTemplate(){
		$workspace = $this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		
		$model = new TemplateMailing($workspace);
		$idfilter = $workspace -> getGet("id");
		
		if($idfilter) {
			$idfilter = new LikeFilter($model->getTable().".id", $workspace -> getGet("id"), _LIKE_STRICT);
		} else {
			$idfilter = new TrueFilter();
		}
		
		$langfilter = $workspace -> getGet("l");
		if($langfilter) {
			$langfilter = new LikeFilter("language", $workspace -> getGet("l"), _LIKE_STRICT );
		} else {
			$langfilter = new TrueFilter();
		}

		$filter = new AndFilter($idfilter,$langfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		$model->getSingle("*", $filter->toSQL());
		
		$html = str_replace('\"', '"', stripslashes($model->text));
		
		
		$translations = $workspace->getTranslation();
		$actual = $translations->getLanguageFromId($workspace -> getGet("l"));
		
		include(_LANGUAGES_PATH . _PATH_SEPARATOR . $actual["code"] . ".php");
		
		if(!preg_match("/%VIEW%/i",$html)){
			$html = _MAILING_VIEW . $html;
		}
		if(!preg_match("/%UNSUBSCRIBE%/i",$html)){
			$html .= _MAILING_UNSUBSCRIBE;
		}
		echo $html;
	}
	
	function execGetElements(){
		$workspace = $this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		echo $this->_getElements();
	}
	
	function _getElements(){
		$workspace = $this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		$return = FileSystem::loadFile(_LIBRARIES_PATH."/templates/email/newsletter/newsletter.html");
		$search = array(
			"%TEMPLATEPATH%",
			"%SITENAME%",
			"%DATENOW%",
			"%TOPTEXT%",
			"%BOTTOMTEXT%"
		);
		$replace = array(
			_SITE_PATH."libraries/templates/email/newsletter",
			Utils::siteOptions("sitename"),
			Utils::parseDate(time(),"%A %d %B %Y"),
			$workspace->getPost("toptext"),
			$workspace->getPost("bottomtext")
		);
		$return = str_replace($search, $replace, $return);
		
		
		preg_match('/%FOREACH%(.*?)%ENDFOREACH%/ms', $return, $matches);
		
		$standar = $matches[1];
		
		$new = "";
		$getting = $workspace->getPost("elements-to-insert");
		
		if(is_array($getting)){
			
			$result = array();
			foreach($getting as $item){
				list($class, $id) = explode("-",$item);
				
				$result[] = $workspace->loadHandler($class,"getSpecific",$id);
			}
			
			foreach($result as $item){
				$img = $item->getParameter("photos");
		
				$search = array(
					"%ITEMDATE%",
					"%ITEMTITLE%",
					"%ITEMTEXT%",
					"%ITEMIMAGE%",
					"%ITEMPERMALINK%"
				);
				$replace = array(
					Utils::parseDate($item->pubdate),
					$item->title,
					Utils::cutText($item->text,300),
					Utils::scaleImage($img[1]['file'],191,121,0,0, $item->title, 'style="border: 1px solid #e9e9e9;"'),
					Utils::siteOptions("siteurl").$item->slug
				);
				$new .= str_replace($search, $replace, $standar);
			}
		}
		
		$final = preg_replace('/%FOREACH%(.*?)%ENDFOREACH%/ms', $new, $return);
		
		return $final;
	}
	
	function execSend(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		$model=new UserMailing($workspace);
		//get categorie
		$cat = new GroupMailing($workspace);
		$workspace->set("categories", $cat->getAll("*"));
		
		$id = $workspace->getRequest("id");
		
		$idfilter = new LikeFilter("id", $id,_LIKE_STRICT);
		
		$filter = new AndFilter($idfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		$model=new Mailing($workspace);
		if($id){
			$model->getSingle("*",$filter->toSQL());
		}
		
		
		Utils::getTemplate($workspace,$module,$task,$model);
	}
	
	function execLoadUsers(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		
		$model=new UserMailing($workspace);
		
		$modelfilter = $workspace -> getGet("category");
		
		if($modelfilter) {
			$modelfilter = new LikeFilter($model ->getTable().".category", $workspace -> getGet("category"), _LIKE_STRICT);
		} else {
			$modelfilter = new TrueFilter();
		}
		
		$filter = new AndFilter($modelfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		$all = $model->getAll("*", $filter->toSQL());
		
		header('Content-type: application/json');

		echo "{\"items\":".json_encode($all)."}";
	}
	
	function execNew(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		$model = new Mailing($workspace);
		
		$parameters = $this->getParameters();
		
		//get categorie
		$cat = new SenderMailing($workspace);
		$workspace->set("senders", $cat->getAll("*"));
		
		
		//get categorie
		$cat = new TemplateMailing($workspace);
		$workspace->set("templates", $cat->getAll("*"));
		
		//get categorie
		$workspace->set("categories", $this->_categories);
		
		
		//loadelements
		$real = $this->getParameters("loadelements");
		$workspace->set("elements", $real->loadelements->element);
		
		$parameter = $this->getParameters();
		
		switch($workspace->getRequest("type")){
			case 1:
				if( ! (string)$parameter->newsletter ){ Utils::redirectOption($module,"view"); return false;}
				
				Utils::getTemplate($workspace,$module,"open-newsletter",$model);
				break;
			case 2:
				if( ! (string)$parameter->mailinglist ){ Utils::redirectOption($module,"view"); return false;}
				
				Utils::getTemplate($workspace,$module,"open-mailing",$model);
				break;	
		}
	}
	
	
	function execNewSender(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		$model = new SenderMailing($workspace);
		
		$parameters = $this->getParameters();
		
		
		Utils::getTemplate($workspace,$module,"open-sender",$model);
	}
	
	function execNewGroup(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		$model = new GroupMailing($workspace);
		
		$parameters = $this->getParameters();
		
		
		$users = new UserMailing($workspace);
		$workspace->set("mailing_users", $users->getPagination());
		
		
		Utils::getTemplate($workspace,$module,"open-group",$model);
	}
	
	
	function execNewUser(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		$model = new UserMailing($workspace);
		
		$parameters = $this->getParameters();
		
		
		Utils::getTemplate($workspace,$module,"open-user",$model);
	}
	
	function execNewTemplate(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		$model = new TemplateMailing($workspace);
		
		$parameters = $this->getParameters();
		
		
		Utils::getTemplate($workspace,$module,"open-template",$model);
	}
	function execDuplicate(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");

		$id = $workspace->getRequest("id");
		
		$idfilter = new LikeFilter("id", $id,_LIKE_STRICT);
		
		$filter = new AndFilter($idfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		$model = new Mailing($workspace);
		$model->getSingle("*",$filter->toSQL());
		if($model->id){
			$model->id = null;
			$model->parameters = null;
			$model->sent = 0;
			$model->created = date("Y-m-d H:i:s");
			$model->lastmodified = date("Y-m-d H:i:s");
			$model->insert();
		
			$workspace->addInfoMessage(sprintf(_LN_INFO_ADD_ITEM, $model->title));
		}
		Utils::redirectOption($module,"view");
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
		$model=new Mailing($workspace);
		if($id){
			$model->getSingle("*",$filter->toSQL());
		}
		$model->bind($post);
		if($model->getParameter("lastsent")){
			$workspace->addErrorMessage(sprintf(_LN_ERROR_NO_UPDATE, $model->title));
			Utils::redirectOption($module,"view");
			return true;
		}

		$parameters = $this->getParameters();
		
		//get categorie
		$cat = new SenderMailing($workspace);
		$workspace->set("senders", $cat->getAll("*"));
		
		
		//get categorie
		$cat = new TemplateMailing($workspace);
		$workspace->set("templates", $cat->getAll("*"));
		
		
		//loadelements
		$real = $this->getParameters("loadelements");
		$workspace->set("elements", $real->loadelements->element);
		
		
		//get categorie
		$workspace->set("categories", $this->_categories);

		switch($workspace->getRequest("type", $model->type)){
			case 1:
				Utils::getTemplate($workspace,$module,"open-newsletter",$model);
				break;
			case 2:
				Utils::getTemplate($workspace,$module,"open-mailing",$model);
				break;	
		}
		
	}
	
	function execOpenSender(){
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
		
		$model = new SenderMailing($workspace);
		if($id){
			$model->getSingle("*",$filter->toSQL());
		}
		$model->bind($post);
		
		$parameters = $this->getParameters();
		
		
		Utils::getTemplate($workspace,$module,"open-sender",$model);
	}
	
	function execOpenGroup(){
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
		
		$model = new GroupMailing($workspace);
		if($id){
			$model->getSingle("*",$filter->toSQL());
		}
		$model->bind($post);
		
		$parameters = $this->getParameters();
		
		$gusers = new GroupUserMailing($workspace);
		$workspace->set("mailing_intersect", $gusers->getAll("*",array("group"=>$model->id)));
		
		
		$users = new UserMailing($workspace);
		$usefilter = $workspace -> getGet("s-search");
		
		if($usefilter) {
			$usefilter_1 = new LikeFilter($users ->getTable().".name", $workspace -> getGet("s-search"));
			$usefilter_2 = new LikeFilter($users ->getTable().".surname", $workspace -> getGet("s-search"));
			$usefilter_2 = new LikeFilter($users ->getTable().".email", $workspace -> getGet("s-search"));
			$usefilter = new OrFilter($usefilter_1,$usefilter_2,$usefilter_2);
		} else {
			$usefilter = new TrueFilter();
		}
		
		$filter = new AndFilter($usefilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		$workspace->set("mailing_users", $users->getPagination("*", $filter->toSQL()));
		
		
		Utils::getTemplate($workspace,$module,"open-group",$model);
	}
	
	function execOpenUser(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");

		$id = $workspace->getRequest("id");
		
		$row=null;
		$post=$workspace->getPost();
		
		$model = new UserMailing($workspace);
		
		$idfilter = new LikeFilter($model->getTable().".id", $id,_LIKE_STRICT);

		
		$filter = new AndFilter($idfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		if($id){
			$model->getSingle("*",$filter->toSQL());
		}
		$model->bind($post);
		
		$parameters = $this->getParameters();
		
		
		Utils::getTemplate($workspace,$module,"open-user",$model);
	}
	
	function execOpenTemplate(){
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
		
		$model = new TemplateMailing($workspace);
		if($id){
			$model->getSingle("*",$filter->toSQL());
		}
		$model->bind($post);
		
		$parameters = $this->getParameters();
		
		Utils::getTemplate($workspace,$module,"open-template",$model);
	}
	
	
	function execSave(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		$translations = $workspace->getTranslation();
		
		$my = $workspace->loadHandler("access");
		
		$db=$workspace->getDatabase();
		
		$listparameters = $this->getParameters();
		
		
		$post = $workspace->getPost();
		
		
		$model=new Mailing($workspace);
		$model->getSingle("*",array("id"=>$post['id']));

		$parameters = unserialize($model->parameters);
		
		$parameters["elements"] = $post["elements-to-insert"];
		
		$workspace->addToPost("parameters",$parameters);	

		$post=$workspace->getPost();
		
		if($post){
			$model->save($post, true);
		}

		$validate = new CheckForm($workspace);
		$valido=$validate->validate($model);

		if(!$valido){
			$this->execOpen();
			exit();
		}
		
		if($post["type"] == 1) $model->text = $this->_getElements();
		
		if($model->id > 0){
			$update=false;
			$model->update();
			
			$workspace->addInfoMessage(sprintf(_LN_INFO_UPDATE_ITEM,$model->title));
		}else{
			$model->insert();
			$workspace->addInfoMessage(sprintf(_LN_INFO_ADD_ITEM,$model->title));
		}
		
		Utils::redirectOption($module,"open",array("id"=>$model->id));
	}
	
	function execSaveSender(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		$translations = $workspace->getTranslation();
		
		$my = $workspace->loadHandler("access");
		
		$db=$workspace->getDatabase();
		
		$post=$workspace->getPost();
		
		
		$model=new SenderMailing($workspace);
		$model->getSingle("*",array("id"=>$post['id']));
		
		$parameters = unserialize($model->parameters);
		
		$workspace->addToPost("parameters",$parameters);	
		
		$post=$workspace->getPost();
		
		if($post){
			$model->save($post, true);
		}

		$validate = new CheckForm($workspace);
		$valido=$validate->validate($model);

		if(!$valido){
			$this->execOpenSender();
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
		
		Utils::redirectOption($module,"senders");
	}
	
	function execSaveGroup(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		$translations = $workspace->getTranslation();
		
		$my = $workspace->loadHandler("access");
		
		$db=$workspace->getDatabase();
		
		$post=$workspace->getPost();
		
		
		$model=new GroupMailing($workspace);
		$model->getSingle("*",array("id"=>$post['id']));
		
		$parameters = unserialize($model->parameters);
		
		$workspace->addToPost("parameters",$parameters);	
		
		$post=$workspace->getPost();
		
		if($post){
			$model->save($post, true);
		}

		$validate = new CheckForm($workspace);
		$valido=$validate->validate($model);

		if(!$valido){
			$this->execOpenSender();
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
		
		Utils::redirectOption($module,"groups");
	}
	
	
	function execSaveGroupUser(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		$translations = $workspace->getTranslation();
		
		$my = $workspace->loadHandler("access");
		
		
		$model=new GroupUserMailing($workspace);
		$model->getSingle("*",array("id"=>$post['id']));
		
		
		$post=$workspace->getPost();
		
		if($post){
			$model->save($post, true);
		}

		$validate = new CheckForm($workspace);
		$valido=$validate->validate($model);

		if(!$valido){
			$this->execOpenSender();
			exit();
		}
		
		if($model->id > 0){
			$update=false;
			$model->update();
			//$workspace->addInfoMessage(sprintf(_LN_INFO_UPDATE_ITEM,$model->title));
		}else{
			$model->insert();
			//$workspace->addInfoMessage(sprintf(_LN_INFO_ADD_ITEM,$model->title));
		}
		
		return true;
	}
	
	function execSaveUser(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		$translations = $workspace->getTranslation();
		
		$my = $workspace->loadHandler("access");
		
		$db=$workspace->getDatabase();
		
		$post=$workspace->getPost();
		
		
		$model=new UserMailing($workspace);
		$model->getSingle("*",array("id"=>$post['id']));
		
		$parameters = unserialize($model->parameters);
		
		$workspace->addToPost("parameters",$parameters);	
		
		$post=$workspace->getPost();
		
		if($post){
			$model->save($post, true);
		}

		$validate = new CheckForm($workspace);
		$valido=$validate->validate($model);
		if(!$model->id){
			$usefilter = new LikeFilter("email", $model->email, _LIKE_STRICT);
			$filter = new AndFilter($usefilter);
			if(!$filter) {
				$filter = new TrueFilter();
			}
			$user = new UserMailing($workspace);
			$user->getSingle("*", $filter->toSQL());
			if($user->id){
				$valido = false;
				$workspace->addErrorMessage(sprintf(_LN_ERROR_UNIQUE,"email",$model->email));
			}
		}
		if(!$valido){
			$this->execOpenUser();
			exit();
		}
		
		if($model->id > 0){
			$update=false;
			$model->update();
			
			$workspace->addInfoMessage(sprintf(_LN_INFO_UPDATE_ITEM,$model->name." ".$model->surname));
		}else{
			$model->insert();
			$workspace->addInfoMessage(sprintf(_LN_INFO_ADD_ITEM,$model->name." ".$model->surname));
		}
		
		Utils::redirectOption($module,"users");
	}
	
	function execSaveTemplate(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		$translations = $workspace->getTranslation();
		
		$my = $workspace->loadHandler("access");
		
		$db=$workspace->getDatabase();
		
		$post=$workspace->getPost();
		
		
		$model=new TemplateMailing($workspace);
		$model->getSingle("*",array("id"=>$post['id']));
		
		$parameters = unserialize($model->parameters);
		
		$workspace->addToPost("parameters",$parameters);	
		
		$post=$workspace->getPost();
		
		if($post){
			$model->save($post, true);
		}

		$validate = new CheckForm($workspace);
		$valido=$validate->validate($model);

		if(!$valido){
			$this->execOpenTemplate();
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
		
		Utils::redirectOption($module,"templates");
	}
	
	function execDelete(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		$db=$workspace->getDatabase();
		
		$id = $workspace->getRequest("id");
		
		$model=new Mailing($workspace);
		$model->getSingle("*",array("id"=>$id));
		$model->delete();
		
		/*
		//cancella LOGS: non so se inserirlo o no
		*/
		$logs = new LogMailing($workspace);
		$all = $logs->getAll("*",array("mailing"=>$id));
		foreach($all as $row){
			$row->delete();
		}
		
		$workspace->addInfoMessage(sprintf(_LN_INFO_DELETE_ITEM,$model->title));
		
		Utils::redirectOption($module);
	}

	
	function execDeleteUser(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		$db=$workspace->getDatabase();
		
		$id = $workspace->getRequest("id");
		
		$model=new UserMailing($workspace);
		$model->getSingle("*",array("id"=>$id));
		
		$parameters = unserialize($model->parameters);
		
		$model->delete();
		
		
		$workspace->addInfoMessage(sprintf(_LN_INFO_DELETE_ITEM,$model->name." ".$model->surname));
		
		Utils::redirectOption($module,"users");
	}

	
	function execDeleteSender(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		$db=$workspace->getDatabase();
		
		$id = $workspace->getRequest("id");
		
		$model=new SenderMailing($workspace);
		$model->getSingle("*",array("id"=>$id));
		
		$parameters = unserialize($model->parameters);
		
		$model->delete();
		
		
		$workspace->addInfoMessage(sprintf(_LN_INFO_DELETE_ITEM,$model->title));
		
		Utils::redirectOption($module,"senders");
	}
	
	function execDeleteGroup(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		$db=$workspace->getDatabase();
		
		$id = $workspace->getRequest("id");
		
		$model=new GroupMailing($workspace);
		$model->getSingle("*",array("id"=>$id));
		
		$parameters = unserialize($model->parameters);
		
		$model->delete();
		
		
		$workspace->addInfoMessage(sprintf(_LN_INFO_DELETE_ITEM,$model->title));
		
		Utils::redirectOption($module,"groups");
	}
	
	function execDeleteGroupUser(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		$db=$workspace->getDatabase();
		
		$id = $workspace->getRequest("user");
		$group = $workspace->getRequest("group");
		
		$model=new GroupUserMailing($workspace);
		$model->getSingle("*",array("user"=>$id,"group"=>$group));
		
		$parameters = unserialize($model->parameters);
		
		$model->delete();
		
		
		//$workspace->addInfoMessage(sprintf(_LN_INFO_DELETE_ITEM,$model->title));
		
		return true;
	}

	
	function execDeleteTemplate(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		$db=$workspace->getDatabase();
		
		$id = $workspace->getRequest("id");
		
		$model=new TemplateMailing($workspace);
		$model->getSingle("*",array("id"=>$id));
		
		$parameters = unserialize($model->parameters);
		
		$model->delete();
		
		
		$workspace->addInfoMessage(sprintf(_LN_INFO_DELETE_ITEM,$model->title));
		
		Utils::redirectOption($module,"templates");
	}


	function execSendTo(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		$post = $workspace->getPost();
		
		$idfilter = new LikeFilter("id", $post["id"],_LIKE_STRICT);
		
		$filter = new AndFilter($idfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		$mail=new Mailing($workspace);
		$mail->getSingle("*",$filter->toSQL());
		
		$idfilter = new LikeFilter("id", $mail->sender,_LIKE_STRICT);
		
		$filter = new AndFilter($idfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		$sender=new SenderMailing($workspace);
		$sender->getSingle("*",$filter->toSQL());
		
		if(is_array($post["send-to"]) || $post["send-all"]){
			
			$model = new UserMailing($workspace);
			// Costruisco filtro ricerca
			
			$modelfilter = $post["send-to"];
			
			if($modelfilter) {
				$modelfilter = new InFilter("g.group", $post["send-to"]);
			} else {
				$modelfilter = new TrueFilter();
			}
			
			$alterfilter = new LikeFilter($model ->getTable().".publish", 1, _LIKE_STRICT );
			
			if($mail->sent == 1){
				$justsent = new LogMailing($workspace);
				$allno = $justsent->getAll("email", array("mailing" => $mail->id));
				$arrex = array();
				foreach($allno as $rox){
					$arrex[] = 	$rox->email;
				}
				$excludefilter = new NotFilter(new InFilter($model ->getTable().".email", $arrex));
			}else{
				$excludefilter = new TrueFilter();
			}
			$filter = new AndFilter($modelfilter, $alterfilter , $excludefilter);
			if(!$filter) {
				$filter = new TrueFilter();
			}
	
			$all = $model->getAll("*", $filter->toSQL());
			
			/*inizio invio email*/
			
			$error = 0;
			$totalSent = 0;
			$errorSent = array();
			
			$config=new Config($workspace);
			$config->getSingle("*",array("id"=>1));
			
			set_time_limit(0);
			ini_set("memory_limit","128M");
			
			$html = $mail->text;
			$html = str_replace("%VIEW%", Utils::makeLink("mailing","see",array("id"=>$mail->id)), $html);
			
			$mail->sent = 1;
			$mail->update();
			
			foreach($all as $user){
			
				$sendmail = new PHPMailer(true); //defaults to using php "mail()"; the true param means it will throw exceptions on errors, which we need to catch
				$sendmail->IsHTML(_MAILER_HTML);
				
				$token = Utils::generateHash();
				$tohtml = str_replace("%VIEW%", Utils::makeLink("mailing","see",array("token"=>$token)), $html);
				
				try {
					$sendmail->AddAddress($user->email, $user->name.' '.$user->surname);
					
					if($config->replyto) $sendmail->AddReplyTo($config->replyto);
					
					$sendmail->SetFrom($sender->email,$sender->title);
					
					$sendmail->Subject = $mail->title;
					$sendmail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
					
					$append = Utils::makeLink("mailing","saw",array("token"=>$token));
					
					$tohtml = str_replace("%USER%", $user->name ."". $user->surname, $tohtml);
					$tohtml = str_replace("%UNSUBSCRIBE%", Utils::makeLink("mailing","unsubscribe",array("email"=>$user->email)), $tohtml);
					
					$sendmail->MsgHTML( $tohtml . ' <img src="'.$append.'" width="1" height="1" />' );
					$sendmail->Send();
					
					
					$log = new LogMailing($workspace);
					$log->mailing = $post["id"];
					$log->email = $user->email;
					$log->status = 1;
					$log->token = $token;
					$log->insert();
					
					
					$totalSent++;
					
				} catch (phpmailerException $e) {
					$workspace->addErrorMessage($user->email." || ".$e->errorMessage());
					$error++;
					
					$log = new LogMailing($workspace);
					$log->mailing = $post["id"];
					$log->email = $user->email;
					$log->status = 0;
					$log->token = $token;
					$log->insert();
					
				} catch (Exception $e) {
					$workspace->addErrorMessage($user->email." XX ".$e->getMessage());
					$error++;
					
					$log = new LogMailing($workspace);
					$log->mailing = $post["id"];
					$log->email = $user->email;
					$log->status = 0;
					$log->token = $token;
					$log->insert();
					
				}
			
			}

			//update dati invio
			
			$parameters = array(
				"lastsent" => date("Y-m-d H:i:s"),
				"totaltosend" => count($all),
				"totalsent" => $totalSent,
			);
			
			$mail->parameters = serialize($parameters);
			$mail->sent = 2;
			$mail->update();
			
			if(count($all) > $error){
				$workspace->addInfoMessage(_LN_INFO_MAILING_SENT);
			}
			
			if(!$error){
				Utils::redirectOption($module);
			}else{
				Utils::redirectOption($module,"send",array("id"=>$mail->id));
			}
			
		}else{
			$workspace->addErrorMessage(_LN_ERROR_MAILING_GROUP);
			Utils::redirectOption($module,"send",array("id"=>$mail->id));
		}
	}

	
	function execValidateUser(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		
		$id = $workspace->getRequest("id");
		$validate = $workspace->getRequest("validate");
		
		if($id>0){
			$model=new UserMailing($workspace);
			$model->getSingle("*",array("id"=>$id));
			$model->publish=$validate;
		

			$model->update(false,false);
			$workspace->addInfoMessage(sprintf(_LN_INFO_UPDATE_ITEM,$model->name." ".$model->surname));
		}
		
		Utils::redirectOption($module,"users");
	}

	function execSaw(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		
		$token = $workspace->getRequest("token");
		
		
		$model=new LogMailing($workspace);
		$model->getSingle("*",array("token"=>$token));
		$model->status = 2;
	

		$model->update(false,false);
		
		return Utils::loadFile(_COMPONENTS_PATH._PATH_SEPARATOR."mailing"._PATH_SEPARATOR."newsletter.gif");
	}
	
	function execSee(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		
		$token = $workspace->getRequest("id");
		
		$mail=new Mailing($workspace);
		$mail->getSingle("*",array("id"=>$token));
		
		$html = $mail->text;

		$html = str_replace('%VIEW%',"#",$html);
		$html = str_replace('%UNSUBSCRIBE%',"#",$html);
		
		
		Utils::getTemplate($workspace,$module,$task,$html, false);
	}
	function execLogs(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		$id = $workspace->getRequest("id");
		
		$mailing = new Mailing($workspace);
		
		$mailing->getSingle("*",array("id" => $id));
		$workspace->set("mailing",$mailing);
		
		$model = new LogMailing($workspace);

		// Costruisco filtro ricerca
		$idfilter = new LikeFilter($model ->getTable().".mailing", $id, _LIKE_STRICT);
		$usefilter = $workspace -> getGet("s-title");
		
		if($usefilter) {
			$usefilter = new LikeFilter($model ->getTable().".email", $workspace -> getGet("s-title"));
		} else {
			$usefilter = new TrueFilter();
		}
		
		$modelfilter = $workspace -> getGet("s-status");
		
		if($modelfilter && (int)$modelfilter < 3) {
			$modelfilter = new LikeFilter($model ->getTable().".status", $workspace -> getGet("s-status"), _LIKE_STRICT);
		} else {
			$modelfilter = new TrueFilter();
		}
		
		$filter = new AndFilter($idfilter,$usefilter,$modelfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		$pagination = $model->getPagination("*", $filter->toSQL());

		
		Utils::getTemplate($workspace,$module,$task,$pagination);
	}
	function execUnsubscribe(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$translations = $workspace->getTranslation();
		$actual = $translations->code();
		
		include(_LANGUAGES_PATH . _PATH_SEPARATOR . $actual . ".php");
		
		$email = $workspace->getRequest("email");
		
		$workspace->set("languages", $translations->getAllLanguages());
		
		$data = sprintf(_MAILING_UNSUBSCRIBE_TEXT,Utils::makeLink("mailing", "unsubscribeMail", array("email"=>$email,"lang"=>$translations->code())));

		Utils::getTemplate($workspace,$module,$task,$data,false);
	}
	function execUnsubscribeMail(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$translations = $workspace->getTranslation();
		$code = $translations->code();
		$workspace->set("languages", $translations->getAllLanguages());
		
		$email = $workspace->getRequest("email");
		
		include(_LANGUAGES_PATH . _PATH_SEPARATOR . $code . ".php");
		
		
		$config=new Config($workspace);
		$config->getSingle("*",array("id"=>1));
		
		$sendmail = new PHPMailer(true); //defaults to using php "mail()"; the true param means it will throw exceptions on errors, which we need to catch
		$sendmail->IsHTML(_MAILER_HTML);
		
		$token = Utils::generateHash();
		
		
		$permalink = Utils::makeLink("mailing","confirmUnsubscribe",array("token" => $token, "lang" => $code));
		
		$tohtml = sprintf(_MAILING_UNSUBSCRIBE_EMAIL,$permalink,$permalink);
		
		try {
			$sendmail->AddAddress($email);
			
			if($config->replyto) $sendmail->AddReplyTo($config->replyto);
			
			$sendmail->SetFrom($config->replyto, $config->sitename . " Mailing System");
			
			$sendmail->Subject = sprintf(_MAILING_UNSUBSCRIBE_EMAIL_TITLE, $config->sitename);
			$sendmail->AltBody = strip_tags($tohtml); // optional - MsgHTML will create an alternate automatically

			
			$sendmail->MsgHTML(nl2br($tohtml));
			$sendmail->Send();
			
			
			$user = new UserMailing($workspace);
			$user->getSingle("*",array("email"=>$email));
			
			$user->token = $token;
			$user->update();
		
			$data = _MAILING_UNSUBSCRIBE_SENT;
	
			Utils::getTemplate($workspace,$module,"unsubscribe",$data,false);
			
		} catch (phpmailerException $e) {
			$data = $e->errorMessage();

			Utils::getTemplate($workspace,$module,"unsubscribe",$data,false);
			
		} catch (Exception $e) {
			$data = $e->getMessage();

			Utils::getTemplate($workspace,$module,"unsubscribe",$data,false);

			
		}
	}
	
	function execConfirmUnsubscribe(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$translations = $workspace->getTranslation();
		$code = $translations->code();
		$workspace->set("languages", $translations->getAllLanguages());
			
		$token = $workspace->getRequest("token");
		
		$user = new UserMailing($workspace);
		$user->getSingle("*", array("token" => $token));
		$user->publish = 0;
		$user->token = null;
		$user->update();
		
		include(_LANGUAGES_PATH . _PATH_SEPARATOR . $code . ".php");
		
		$data = _MAILING_UNSUBSCRIBE_COMPLETE;
		
		Utils::getTemplate($workspace,$module,"unsubscribe",$data,false);
	}
	
}
