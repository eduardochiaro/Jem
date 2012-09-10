<?php
class MediaFrontend extends Frontend{
	
	function loadPhotogallery($id){
		$workspace = JEM::getInstance();
		

		$idfilter = new LikeFilter("category", $id,_LIKE_STRICT);
		$typedfilter = new LikeFilter("format", "photo",_LIKE_STRICT);
		$publishfilter = new LikeFilter("publish", 1,_LIKE_STRICT);
		
		$filter = new AndFilter($idfilter , $typedfilter, $publishfilter);
		if(!$filter) {
			$filter = new TrueFilter();
		}
		
		$model = new Media($workspace);
		$all = array();
		if($id){
			$all = $model->getAll("*",$filter->toSQL());
			return $all;
		}
		return false;
	}
}