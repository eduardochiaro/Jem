<?php
/**
 * @title			events
 */


class eventsController extends Controller{
	
	function execView(){
		$workspace = $this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");

		$model = new Event($workspace);
		// Costruisco filtro ricerca
		$usefilter = $workspace -> getGet("s-title");
		
		if($usefilter) {
			$usefilter = new LikeFilter("t.text_1", $workspace -> getGet("s-title"));
		} else {
			$usefilter = new TrueFilter();
		}
		
		$modelfilter = $workspace -> getGet("s-category");
		
		if($modelfilter) {
			$modelfilter = new LikeFilter($model ->getTable().".category", $workspace -> getGet("s-category"), _LIKE_STRICT);
		} else {
			$modelfilter = new TrueFilter();
		}
		
		$filter = new AndFilter($usefilter,$modelfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		$pagination = $model->getPagination("*", $filter->toSQL());
		
		//get categorie
		$cat = new Category($workspace);
		$modelfilter = $model -> getTable();
		
		if($modelfilter) {
			$modelfilter = new LikeFilter($cat ->getTable().".model", $model -> getTable(), _LIKE_STRICT);
		} else {
			$modelfilter = new TrueFilter();
		}
		
		$filter = new AndFilter($modelfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		$workspace->set("categories", $cat->getAll("*", $filter->toSQL()));
		
		Utils::getTemplate($workspace,$module,$task,$pagination);
	}
	
	function execSlug(){
		$workspace = $this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		
		$model = new Event($workspace);
		$idfilter = $workspace -> getGet("id");
		
		if($idfilter) {
			$idfilter = new NotFilter(new LikeFilter($model->getTable().".id", $workspace -> getGet("id")), _LIKE_STRICT);
		} else {
			$idfilter = new TrueFilter();
		}
		
		$langfilter = $workspace -> getGet("l");
		if($langfilter) {
			$langfilter = new LikeFilter("language", $workspace -> getGet("l"), _LIKE_STRICT );
		} else {
			$langfilter = new TrueFilter();
		}
		
		
		$usefilter = $workspace -> getGet("text");
		
		if($usefilter) {
			$usefilter = new LikeFilter("text_2", Formatting::sanitizeTitleWithDashes($workspace -> getGet("text")), _LIKE_STRICT);
		} else {
			$usefilter = new TrueFilter();
		}
		
		$filter = new AndFilter($usefilter, $langfilter, $idfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		$model->getSingle("*", $filter->toSQL());

		if($model->id > 0){
			$ls = (int)$workspace->get("loadSlug") + 1;
			$workspace->addToGet("text" , $workspace -> getGet("text")." ".$ls);
			$workspace->set("loadSlug",$ls);
			
			$this->execSlug();
			exit();
		}
		
		echo Formatting::sanitizeTitleWithDashes($workspace->getGet("text"));
	}
	function execNew(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		$workspace->set("seo",$workspace->loadHandler("seo"));

		
		$model = new Event($workspace);
		
		
		$parameters = $this->getParameters();
		
		$workspace->set("slug_base",$parameters->slug);
		$workspace->set("number_images",$parameters->photos);
		$workspace->set("number_files",$parameters->files);
		
		//get categorie
		$cat = new Category($workspace);
		$modelfilter = $model -> getTable();
		
		if($modelfilter) {
			$modelfilter = new LikeFilter($cat ->getTable().".model", $modelfilter, _LIKE_STRICT);
		} else {
			$modelfilter = new TrueFilter();
		}
		
		$filter = new AndFilter($modelfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		$workspace->set("categories", $cat->getAll("*", $filter->toSQL()));
		
		
		//get gallery
		$cat = new Category($workspace);
		$workspace->set("photo", $cat->getAll("*", $cat->getTable().".model = 'photo'"));
		$workspace->set("video", $cat->getAll("*", $cat->getTable().".model = 'video'"));
		
		Utils::getTemplate($workspace,$module,"open",$model);
		
	}
	
	function execOpen(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		$workspace->set("seo",$workspace->loadHandler("seo"));

		
		$id = $workspace->getRequest("id");
		
		$row=null;
		$post=$workspace->getPost();
		
		
		
		$idfilter = new LikeFilter("id", $id,_LIKE_STRICT);

		
		$filter = new AndFilter($idfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		
		$model=new Event($workspace);
		if($id){
			$model->getSingle("*",$filter->toSQL());
		}
		$model->bind($post);
		
		$parameters = $this->getParameters();
		
		$workspace->set("slug_base",$parameters->slug);
		$workspace->set("number_images",$parameters->photos);
		$workspace->set("number_files",$parameters->files);
		
		//get categorie
		$cat = new Category($workspace);
		$modelfilter = $model -> getTable();
		
		if($modelfilter) {
			$modelfilter = new LikeFilter($cat ->getTable().".model", $model -> getTable(), _LIKE_STRICT);
		} else {
			$modelfilter = new TrueFilter();
		}
		
		$filter = new AndFilter($modelfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		$workspace->set("categories", $cat->getAll("*", $filter->toSQL()));
		
		
		//get gallery
		$cat = new Category($workspace);
		$workspace->set("photo", $cat->getAll("*", $cat->getTable().".model = 'photo'"));
		$workspace->set("video", $cat->getAll("*", $cat->getTable().".model = 'video'"));
		

		Utils::getTemplate($workspace,$module,"open",$model);
		
	}
	
	
	function execSave(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		$translations = $workspace->getTranslation();
		
		$my = $workspace->loadHandler("access");
		
		$db=$workspace->getDatabase();
		
		$listparameters = $this->getParameters();
		$nphotos = (int)$listparameters->photos;
		$nfiles = (int)$listparameters->files;
	
		$post=$workspace->getPost();
		
		
		$model=new Event($workspace);
		$model->getSingle("*",array("id"=>$post['id']));
		
		$parameters = unserialize($model->parameters);
		
		
		for($i = 1; $i <= $nphotos; $i++){
			if($post['delete-photo-'.$i]){
				$cache_file = Utils::getCacheName($parameters['photos'][$i]["file"]);
				FileSystem::searchAndDestroy(_FRAMEWORK_UPLOADS_PATH . _PATH_SEPARATOR."cache",$cache_file);
				FileSystem::searchAndDestroy(_FRAMEWORK_UPLOADS_PATH."/contents", $parameters['photos'][$i]["file"]);
				unset($parameters['photos'][$i]);
			}
			$photo = Upload::uploadFile('photo-'.$i, "contents", $workspace);
			if($photo){	
				$parameters['photos'][$i]["file"] = $photo;
				new ScaleAndSaveImage($photo,_PHOTOS_W_,_PHOTOS_H_);
			}
		}

		for($i = 1; $i <= $nfiles; $i++){
			if($post['delete-file-'.$i]){
				$cache_file = Utils::getCacheName($parameters['files'][$i]["file"]);
				FileSystem::searchAndDestroy(_FRAMEWORK_UPLOADS_PATH."/contents", $parameters['files'][$i]["file"]);
				unset($parameters['files'][$i]);
			}
			$file = Upload::uploadFile('file-'.$i, "contents", $workspace);
			if( $post['title-file-'.$i]) $parameters['files'][$i]["title"] = $post['title-file-'.$i];
			if($file){	
				$parameters['files'][$i]["file"] = $file;
			}
		}
		
		if($post["gallery_photo"]){
			$parameters["gallery_photo"] = $post["gallery_photo"];
		}
	
		if($post["gallery_video"]){
			$parameters["gallery_video"] = $post["gallery_video"];
		}
		
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
		
		if($model->id > 0){
			$update=false;
			$model->update();
			
			$workspace->addInfoMessage(sprintf(_LN_INFO_UPDATE_ITEM,$model->title));
		}else{
			$model->insert();
			$workspace->addInfoMessage(sprintf(_LN_INFO_ADD_ITEM,$model->title));
		}
		$workspace->set("model-seo", $model);
		$seo = $workspace->loadHandler("seo");
		$seo->execSave();
		
		Utils::redirectOption($module);
		
		
	}
	
	function execDelete(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		$db=$workspace->getDatabase();
		
		$id = $workspace->getRequest("id");
		
		$model=new Event($workspace);
		$model->getSingle("*",array("id"=>$id));
		
		$parameters = unserialize($model->parameters);
		
		
		
		$listparameters = $this->getParameters();
		$nphotos = (int)$listparameters->photos;
		$nfiles = (int)$listparameters->files;
		
		
		for($i = 1; $i <= $nphotos; $i++){
			$cache_file = Utils::getCacheName($parameters['photos'][$i]["file"]);
			FileSystem::searchAndDestroy(_FRAMEWORK_UPLOADS_PATH . _PATH_SEPARATOR."cache",$cache_file);
			FileSystem::searchAndDestroy(_FRAMEWORK_UPLOADS_PATH, $parameters['photos'][$i]["file"]);
		}

		for($i = 1; $i <= $nfiles; $i++){
			$cache_file = Utils::getCacheName($parameters['files'][$i]["file"]);
			FileSystem::searchAndDestroy(_FRAMEWORK_UPLOADS_PATH, $parameters['files'][$i]["file"]);
		}
		
		$workspace->set("model-seo", $model);
		$seo = $workspace->loadHandler("seo");
		$seo->execDelete();
		
		$model->delete();
		
		
		$workspace->addInfoMessage(sprintf(_LN_INFO_DELETE_ITEM,$model->title));
		
		Utils::redirectOption($module);
	}

	
	function execValidate(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		
		$id = $workspace->getRequest("id");
		$validate = $workspace->getRequest("validate");
		
		if($id>0){
			$model=new Event($workspace);
			$model->getSingle("*",array("id"=>$id));
			$model->publish=$validate;
		

			$model->update(false,false);
			$workspace->addInfoMessage(sprintf(_LN_INFO_UPDATE_ITEM,$model->title));
		}
		
		Utils::redirectOption($module);
		
		
	}



}
