<?php

class EventsFrontend extends Frontend{

	function loadAll(){
		$workspace = JEM::getInstance();
			
		$publishfilter = new LikeFilter("publish", 1,_LIKE_STRICT);
		
		$filter = new AndFilter($publishfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		$model = new Event($workspace);

		$pagination = $model->getPagination("*",$filter->toSQL());
		
		return $pagination;
	}
	
	function loadSingleFromId($id = false){
		$workspace = JEM::getInstance();
		if(!$id) $id = $workspace->get("idelement");
		
		$model = new Event($workspace);
		$row=null;
		$idfilter = new LikeFilter($model->getTable().".id", $id,_LIKE_STRICT);
		$publishfilter = new LikeFilter($model->getTable().".publish", 1,_LIKE_STRICT);
		
		$filter = new AndFilter($idfilter, $publishfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}

		if($id){
			$model->getSingle("*",$filter->toSQL());
		}

		if($model->id){
			return $model;
		}else{
			Log::error("404");
		}
	}	
	
	function loadSingleFromSlug($id = false){
		$workspace = JEM::getInstance();
		if(!$id) $id = $workspace->get("idelement");
		
		$model = new Event($workspace);
		$row=null;
		$idfilter = new LikeFilter("t.text_2", $id,_LIKE_STRICT);
		$publishfilter = new LikeFilter($model->getTable().".publish", 1,_LIKE_STRICT);
		
		$filter = new AndFilter($idfilter, $publishfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}

		if($id){
			$model->getSingle("*",$filter->toSQL());
		}

		if($model->id){
			return $model;
		}else{
			Log::error("404");
		}
	}	
}