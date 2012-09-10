<?php
class ConfigFrontend extends Frontend{
	function load(){
		$workspace = JEM::getInstance();
		
		$row=null;
		$idfilter = new LikeFilter("id", 1, _LIKE_STRICT);
		
		$filter = new AndFilter($idfilter);

		$model = new Config($workspace);
		$model->getSingle("*",$filter->toSQL());


		if($model->id){
			return $model;
		}
		
		Log::error($model);
	}
}