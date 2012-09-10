<?php
/**
 * @title			categories
 */


class categoriesController extends Controller{
	
	function execView(){
		$workspace = $this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		
		$workspace->set("toModel",$this->getNamed());

		$model = new Category($workspace);
		// Costruisco filtro ricerca
		$usefilter = $workspace -> getGet("s-title");
		
		if($usefilter) {
			$usefilter = new LikeFilter("t.text_1", $workspace -> getGet("s-title"));
		} else {
			$usefilter = new TrueFilter();
		}
		
		$modelfilter = $workspace -> getGet("model");
		
		if($modelfilter) {
			$modelfilter = new LikeFilter($model ->getTable().".model", $workspace -> getGet("model"), _LIKE_STRICT);
		} else {
			Utils::redirectOption("index");
		}
		
		$filter = new AndFilter($usefilter,$modelfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		$pagination = $model->getPagination("*", $filter->toSQL());
		
		Utils::getTemplate($workspace,$module,$task,$pagination);
	}
	
	function execNew(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		$workspace->set("toModel",$this->getNamed());

		$model=new Category($workspace);
		
		$model->model = $workspace->getRequest("model");
		
		Utils::getTemplate($workspace,$module,"open",$model);
		
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
		
		
		$model=new Category($workspace);
		if($id){
			$model->getSingle("*",$filter->toSQL());
		}
		$model->bind($post);

		Utils::getTemplate($workspace,$module,"open",$model);
		
	}
	
	
	function execSave(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		$translations = $workspace->getTranslation();
		
		$my = $workspace->loadHandler("access");
		$post=$workspace->getPost();
		
		$model=new Category($workspace);
		$model->getSingle("*",array("id"=>$post['id']));

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

		
		Utils::redirectOption($module,"view",array("model"=>$model->model));
		
		
	}
	
	function execDelete(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		$id = $workspace->getRequest("id");
		
		$model=new Category($workspace);
		$model->getSingle("*",array("id"=>$id));
		$model->delete();
		
		$workspace->addInfoMessage(sprintf(_LN_INFO_DELETE_ITEM,$model->title));
		
		Utils::redirectOption($module,"view",array("model"=>$model->model));
	}

	
	function execValidate(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		
		$id = $workspace->getRequest("id");
		$validate = $workspace->getRequest("validate");
		
		if($id>0){
			$model=new Category($workspace);
			$model->getSingle("*",array("id"=>$id));
			$model->publish=$validate;
		

			$model->update(false,false);
			$workspace->addInfoMessage(sprintf(_LN_INFO_UPDATE_ITEM,$model->title));
		}
		
		Utils::redirectOption($module,"view",array("model"=>$model->model));
		
		
	}

}
