<?php
$slug = $workspace->get("slug_base");
$seo = $workspace->get("seo");
$translations = $workspace->getTranslation();
$number_images = $workspace->get("number_images");
$number_files = $workspace->get("number_files");
$contacts_system = $workspace->get("contacts_system");
$photo_system = $workspace->get("photo_system");
$video_system = $workspace->get("video_system");


$categories = $workspace->get("replies");
foreach($categories as $category){
	$opzioni[$category->id]=$category->title;
}

$photo = $workspace->get("photo");
$opzioni_p[""] = " - nessuna - ";
foreach($photo as $p){
	$opzioni_p[$p->id]=$p->title;
}

$video = $workspace->get("video");
$opzioni_v[""] = " - nessuna - ";
foreach($video as $v){
	$opzioni_v[$v->id]=$v->title;
}

if($data->id){
	$name="modifica contenuto '".$data->title."'";
}else{
	$name="inserisci contenuto";
}
?><div id="bordo"> <!--inizio #bordo-->
     
     	<h2 class="titolo">Gestione Contenuti - <?php echo $name?></h2>
        
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
                 <?php if($contacts_system):?>
                <fieldset>
                <h2>contatti</h2>
                <div class="personal">
        <?php echo Forms::element("motivo","contacts",Forms::multiselect("contacts", $opzioni, $data->getParameter("contacts"),"lunga",''),"generica");?>
        </div>
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