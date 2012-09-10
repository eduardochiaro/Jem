<?php
/**
 * @title			Seo
 */


class seoController extends Controller{
	
	function execNew(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");

		
		$model=new Seo($workspace);
		
		Utils::getTemplate($workspace,$module,"open",$model, false);
		
	}
	
	function execOpen($model, $reference){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");

		
		$modelfilter = new LikeFilter("seo.model", $model,_LIKE_STRICT);
		$reffilter = new LikeFilter("seo.reference", $reference,_LIKE_STRICT);
		
		
		$filter = new AndFilter($reffilter,$modelfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		
		$model=new Seo($workspace);
		if($reference){
			$model->getSingle("*",$filter->toSQL());
		}
		

		Utils::getTemplate($workspace,$module,"open",$model, false);
		
	}
	
	
	function execSave(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		$translations = $workspace->getTranslation();
		
		$my = $workspace->loadHandler("access");
		
		$ms = $workspace->get("model-seo");
		
		
		//$post=$workspace->getPost();
		$post = array(
					"model" => $ms->getTable(),
					"reference" => $ms->id
				);

		$model=new Seo($workspace);
		$model->getSingle("*",$post);
		if(!$model->id){
			$model->save($post, true);
		}

/*		$validate = new CheckForm($workspace);
		$valido=$validate->validate($model);

		if(!$valido){
			//$this->execOpen();
			Log::error($model);
			exit();
		}
		*/
		
		if($model->id > 0){
			$update=false;
			$model->update();
			//$workspace->addInfoMessage(sprintf(_LN_INFO_UPDATE_ITEM,$model->username));
		}else{
			$model->insert();
			//$workspace->addInfoMessage(sprintf(_LN_INFO_ADD_ITEM,$model->username));
		}
			
		return true;
		//Utils::redirectOption($module);
		
		
	}
	
	function execDelete(){
		$workspace=$this->getWorkspace();
		$module=$this->getModule();
		$task=$this->getTask();
		
		$my = $workspace->loadHandler("access");
		
		$ms = $workspace->get("model-seo");
		
		$post = array(
					"model" => $ms->getTable(),
					"reference" => $ms->id
				);

		$model=new Seo($workspace);
		$model->getSingle("*",$post);
		
		
		$model->delete();
		
		//$workspace->addInfoMessage(sprintf(_LN_INFO_DELETE_ITEM,$model->username));
		return true;
		//Utils::redirectOption($module);
	}


}
