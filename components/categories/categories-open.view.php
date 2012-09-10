<?php
$toModel = $workspace->get("toModel");
$actualModel = $workspace->getRequest("model",$data->model);

$translations = $workspace->getTranslation();

if($data->id){
	$name="modifica categoria '".$data->title."'";
}else{
	$name="inserisci categoria";
}
?><div id="bordo"> <!--inizio #bordo-->
     
     	<h2 class="titolo">Gestione Categorie - <?php echo $toModel->$actualModel?> - <?php echo $name?></h2>
        
        <div id="maschera-form"> <!--inizio #maschera-form-->
        
        	<form action="<?php echo Utils::makeLink($module,"save")?>" enctype="multipart/form-data" method="post" class="form-generico">
 
                <fieldset>
                    <div class="spazio-20"></div>
<?php 

	$args = array(
				  array(
						"text"=>"titolo",
						"type"=>"text",
						"id"=>"nome",
						"class"=>"lungo-1",
						"required"=>true,
						"editor"=>false,
						"standard"=>$data->title,
						)
				  );
	$translations->makeTabs($args,$data->getTable(),$data->id);
	
?>
  <p class="lungo-1"> <label for="subtitle" class="generica">Stato</label>
  <?php echo Forms::radio("publish","1",$data->publish,'campi-radio','',"1");?><em>online</em>
  <?php echo Forms::radio("publish","0",$data->publish,'campi-radio','',"0");?><em>offline</em>
  </p>                

                    <span class="clear spazio-10"></span>
                    
                    <p class="pulsante">
						<?php echo Forms::button("invia modifica","submit",'pulsante-invia')?>
                  	</p>
                    
              </fieldset>
	<?php echo Forms::hidden("model",$data->model)?>
	<?php echo Forms::hidden("id",$data->id)?>
	<?php echo Forms::hidden("m",$module)?>
	<?php echo Forms::hidden("t","save")?>
            
          </form>
            
        	</div><!--fine #maschera-form-->
            
            
        
        <span class="clear"></span>
        
        <span class="spazio-10"></span>
        
        
     </div>