<?php
$slug = $workspace->get("slug_base");
$seo = $workspace->get("seo");
$translations = $workspace->getTranslation();
$number_images = $workspace->get("number_images");
$number_files = $workspace->get("number_files");

if($data->id){
	$name="modifica news '".$data->title."'";
}else{
	$name="inserisci news";
}
?><div id="bordo"> <!--inizio #bordo-->
     
     	<h2 class="titolo">Gestione News - <?php echo $name?></h2>
        
        <div id="maschera-form"> <!--inizio #maschera-form-->
        
        	<form action="<?php echo Utils::makeLink($module,"save")?>" enctype="multipart/form-data" method="post" class="form-generico">
 
                <fieldset>
                <div class="personal">
                <p class="lungo-1">
                    	<?php echo Forms::element("Data pubblicazione","pubdate",Forms::text("pubdate",Utils::parseDate($data->pubdate),"pickerdate corto",'maxlength="220" size="20"'),"generica");?>
                </p>
                </div>
                    <div class="spazio-20"></div>
<?php 

	$args = array(
				  array(
						"text"=>"titolo",
						"type"=>"text",
						"id"=>"nome",
						"class"=>"lungo-1 updateslug",
						"required"=>true,
						"editor"=>false,
						"standard"=>$data->title,
						),
				  array(
						"text"=>"permalink",
						"type"=>"text",
						"id"=>"slug",
						"class"=>"lungo-1 writeslug",
						"required"=>true,
						"editor"=>false,
						"standard"=>$data->title,
						"pretext"=>_SITE_PATH.$slug
						),
				  array(
						"text"=>"occhiello",
						"type"=>"textarea",
						"id"=>"pretext",
						"class"=>"lungo-2",
						"required"=>false,
						"editor"=>false,
						"standard"=>$data->pretext
						),
				  array(
						"text"=>"descrizione",
						"type"=>"textarea",
						"id"=>"text",
						"required"=>false,
						"editor"=>true,
						"standard"=>$data->text
						)
				  );
	$translations->makeTabs($args,$data->getTable(),$data->id);
	
?>
                    <p class="lungo-1"> <label for="subtitle" class="generica">Stato</label>
  <?php echo Forms::radio("publish","1",$data->publish,'campi-radio','',"1");?><em>online</em>
  <?php echo Forms::radio("publish","0",$data->publish,'campi-radio','',"0");?><em>offline</em>
  </p>          
                 </fieldset>   
                    <?php if($photo_system || $video_system):?>
                <fieldset>
                <h2>Gallerie</h2>

                  <?php $val = $data->getParameter("gallery_photo")?>
                 <?php for($i=0; $i < (int)$photo_system; $i++):?>
                  <p class="lungo-1">
				<?php echo Forms::element("Fotografica ".($i+1),"gallery_photo",Forms::select("gallery_photo[]", $opzioni_p, $val[$i],"lunga",''),"generica");?>
                </p>
                <?php endfor;?>

                  <?php $val = $data->getParameter("gallery_video")?>
                 <?php for($i=0; $i < (int)$video_system; $i++):?>
                  <p class="lungo-1">
				<?php echo Forms::element("Video ".($i+1),"gallery_video",Forms::select("gallery_video[]", $opzioni_v, $val[$i],"lunga",''),"generica");?>
                </p>
                <?php endfor;?>

                 </fieldset> 
                 <?php endif?>   

                 <?php if($number_images > 0):?>
                <fieldset>
                <h2>immagini</h2>
                
					<?php FormUploads::images($workspace,$data->getParameter("photos"),$number_images,"lungo-1");?>
                 </fieldset> 
                 <?php endif?>
                 <?php if($number_files > 0):?>
                <fieldset>
                <h2>documenti</h2>
					<?php FormUploads::files($workspace,$data->getParameter("files"),$number_files,"flottante-c");?>
                 </fieldset> 
                 <?php endif?>
                    <?php $seo->execOpen($data->getTable(),$data->id);?>
                    
                    <span class="clear spazio-10"></span>
                    
                    <p class="pulsante">
						<?php echo Forms::button("invia modifica","submit",'pulsante-invia')?>
                  	</p>
                    
              </fieldset>
	<?php echo Forms::hidden("id",$data->id)?>
	<?php echo Forms::hidden("m",$module)?>
	<?php echo Forms::hidden("t","save")?>
            
          </form>
            
        	</div><!--fine #maschera-form-->
            
            
        
        <span class="clear"></span>
        
        <span class="spazio-10"></span>
        
        
     </div>