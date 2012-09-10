<?php

class CategoriesFrontend extends Frontend{

	function loadAll($model){
		$workspace = JEM::getInstance();
		
		if($model){
			$modelfilter = new LikeFilter($model->getTable().".model", $model,_LIKE_STRICT);
			$publishfilter = new LikeFilter("publish", 1,_LIKE_STRICT);
			
			$filter = new AndFilter($modelfilter, $publishfilter);
			if(!$filter) {
				$filter = new TrueFilter();
			}
			
			$model = new Category($workspace);
	
			$all = $model->getAll("*",$filter->toSQL());
			
			return $all;
		}
	}
	
	function loadSingle($id = false){
		$workspace = JEM::getInstance();
		if(!$id) $id = $workspace->get("idelement");
		
		$model = new Category($workspace);

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
}