<?php
/**
 * @title			media
 */


class mediaController extends Controller{
	
	function execView(){
		$workspace = $this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		$workspace->set("toModel",$this->getNamed());

		$model = new Media($workspace);
		// Costruisco filtro ricerca
		$usefilter = $workspace -> getGet("s-title");
		
		if($usefilter) {
			$usefilter = new LikeFilter("t.text_1", $workspace -> getGet("s-title"));
		} else {
			$usefilter = new TrueFilter();
		}
		
		$modelfilter = $workspace -> getGet("limitation");
		
		if($modelfilter) {
			$modelfilter = new LikeFilter($model ->getTable().".format", $workspace -> getGet("limitation"), _LIKE_STRICT);
		} else {
			Utils::redirectOption("index");
		}
		
		$filter = new AndFilter($usefilter,$modelfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		$pagination = $model->getPagination("*", $filter->toSQL());
		
		Utils::getTemplate($workspace,$module,$task."-".$workspace -> getGet("limitation"),$pagination);
	}
	
	function execNew(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		$workspace->set("toModel",$this->getNamed());

		$model=new Media($workspace);
		
		$model->format = $workspace->getRequest("limitation");
		
		//get categorie
		$cat = new Category($workspace);
		$modelfilter = $workspace -> getGet("limitation");
		
		if($modelfilter) {
			$modelfilter = new LikeFilter($cat ->getTable().".model", $workspace -> getGet("limitation"), _LIKE_STRICT);
		} else {
			Utils::redirectOption("index");
		}
		
		$filter = new AndFilter($modelfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		$workspace->set("categories", $cat->getAll("*", $filter->toSQL()));
		
		Utils::getTemplate($workspace,$module,"open-".$model->format,$model);
		
	}
	
	function execOpen(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		$workspace->set("toModel",$this->getNamed());
		
		$id = $workspace->getRequest("id");
		
		$post=$workspace->getPost();
		
		$idfilter = new LikeFilter("id", $id,_LIKE_STRICT);

		$filter = new AndFilter($idfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		$model=new Media($workspace);
		if($id){
			$model->getSingle("*",$filter->toSQL());
		}
		$model->bind($post);
		
		//get categorie
		$cat = new Category($workspace);
		$modelfilter = $model -> format;
		
		if($modelfilter) {
			$modelfilter = new LikeFilter($cat ->getTable().".model", $model -> format, _LIKE_STRICT);
		} else {
			Utils::redirectOption("index");
		}
		
		$filter = new AndFilter($modelfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		$workspace->set("categories", $cat->getAll("*", $filter->toSQL()));

		Utils::getTemplate($workspace,$module,"open-".$model->format,$model);
		
	}
	
	
	function execSave(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		$translations = $workspace->getTranslation();
		
		$my = $workspace->loadHandler("access");
		
		
		if($post['delete-photo-1']){
			$cache_file = Utils::getCacheName($model->media);
			FileSystem::searchAndDestroy(_FRAMEWORK_UPLOADS_PATH . _PATH_SEPARATOR."cache",$cache_file);
			FileSystem::searchAndDestroy(_FRAMEWORK_UPLOADS_PATH."/media", $model->media);
			$model->media = "";
		}
		$photo = Upload::uploadFile('photo-1', "media", $workspace);
		if($photo){	
			$workspace->addToPost("media",$photo);	
			new ScaleAndSaveImage($photo,_PHOTOS_W_,_PHOTOS_H_);
		}
		$post=$workspace->getPost();
		
		$model=new Media($workspace);
		$model->getSingle("*",array("id"=>$post['id']));

		if($post){
			$model->save($post, true);
		}

		$validate = new CheckForm($workspace);
		$valido=$validate->validate($model);


		if(!$valido){
			FileSystem::searchAndDestroy(_FRAMEWORK_UPLOADS_PATH."/media",$model->media);
			$this->execOpen();
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

		
		Utils::redirectOption($module,"view",array("limitation"=>$model->format));
		
		
	}
	
	function execDelete(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		$id = $workspace->getRequest("id");
		
		$model=new Media($workspace);
		$model->getSingle("*",array("id"=>$id));
		
		
		$cache_file = Utils::getCacheName($model->media);
		FileSystem::searchAndDestroy(_FRAMEWORK_UPLOADS_PATH . _PATH_SEPARATOR."cache",$cache_file);
		FileSystem::searchAndDestroy(_FRAMEWORK_UPLOADS_PATH."/media", $model->media);
		
		$model->delete();
		
		$workspace->addInfoMessage(sprintf(_LN_INFO_DELETE_ITEM,$model->title));
		
		Utils::redirectOption($module,"view",array("limitation"=>$model->format));
	}

	
	function execValidate(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		
		$id = $workspace->getRequest("id");
		$validate = $workspace->getRequest("validate");
		
		if($id>0){
			$model=new Media($workspace);
			$model->getSingle("*",array("id"=>$id));
			$model->publish=$validate;
		

			$model->update(false,false);
			$workspace->addInfoMessage(sprintf(_LN_INFO_UPDATE_ITEM,$model->title));
		}
		
		Utils::redirectOption($module,"view",array("limitation"=>$model->format));
		
		
	}

}
