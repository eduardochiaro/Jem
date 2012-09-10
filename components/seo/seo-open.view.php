
<span class="clear spazio-25"></span>
<span class="clear spazio-25"></span>
<fieldset>
<?php 
$translations = $workspace->getTranslation();

	$args = array(
				  array(
						"text"=>"Tag Title",
						"type"=>"text",
						"id"=>"tag_title",
						"class"=>"lungo-1",
						"required"=>false,
						"editor"=>false,
						),
				  array(
						"text"=>"Tag Description",
						"type"=>"textarea",
						"id"=>"tag_description",
						"class"=>"lungo-2",
						"required"=>false,
						"editor"=>false,
						),
				  array(
						"text"=>"Tag Metatag",
						"type"=>"text",
						"id"=>"tag_metatag",
						"class"=>"lungo-1",
						"required"=>false,
						"editor"=>false,
						),
				  );
	$translations->makeTabs($args,$data->getTable(),$data->id);
	
?>
</fieldset>