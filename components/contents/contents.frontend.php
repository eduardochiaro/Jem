<?php
class ContentsFrontend extends Frontend{
	
	function loadSingleFromId($id = false){
		$workspace = JEM::getInstance();
		if(!$id) $id = $workspace->get("idelement");
		
		$model = new Content($workspace);
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
		
		$model = new Content($workspace);
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