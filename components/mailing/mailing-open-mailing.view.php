<?php
$translations = $workspace->getTranslation();

$categories = $workspace->get("categories");
$opzioni = array();
foreach($categories as $id => $title){
	$opzioni[$id] = $title;
}

$senders = $workspace->get("senders");
$opzioni_s = array();
foreach($senders as $sender){
	$opzioni_s[$sender->id] = $sender->title . " <" . $sender->email . ">";
}

$templates = $workspace->get("templates");
$opzioni_t = array();
foreach($templates as $template){
	$opzioni_t[$template->id] = $template->title;
}


$translations = $translations->getAllLanguages();

$opzioni_l = array();
foreach($translations as $tran){
	$opzioni_l[$tran["id"]] = $tran["language"];
}


if($data->id){
	$name="modifica mailinglist '".$data->title."'";
}else{
	$name="inserisci mailinglist";
}
?><div id="bordo"> <!--inizio #bordo-->
     
     	<h2 class="titolo">Gestione Mailinglist - <?php echo $name?></h2>
        
        <div id="maschera-form"> <!--inizio #maschera-form-->
        
        	<form action="<?php echo Utils::makeLink($module,"save")?>" enctype="multipart/form-data" method="post" class="form-generico">
 
                <fieldset>
                <p class="lungo-1">
                    	<?php echo Forms::element("Mittente","sender",Forms::select("sender",$opzioni_s,$data->sender,"lunga-1",''),"generica");?>
                </p>
                    <div class="spazio-20"></div>
                    
                    
                <p class="lungo-1">
                    	<?php echo Forms::element("Titolo","title",Forms::text("title",$data->title,"lungo-1"),"generica");?>
                    </p>
                    
                
                <p class="lungo-1">
                    	<?php echo Forms::element("Scegli Template","template",
										Forms::select("template",$opzioni_t,'',"lunga",'') . " " .
										Forms::select("language",$opzioni_l,'',"media",'')
									,"generica");?>
                                    
						<?php echo Forms::button("carica questo template","button",'','id="sendtemplate"')?>
                </p>
                <p class="lungo-1">
                    	<?php echo Forms::element("Corpo Email","text",Forms::textarea("text",5,10,$data->text,"editor-full"),"generica");?>
                    </p>
                    
                    
                    <span class="clear spazio-10"></span>
                    
                    <p class="pulsante">
						<?php echo Forms::button("invia modifica","submit",'pulsante-invia')?>
                  	</p>
                    
              </fieldset>
	<?php echo Forms::hidden("id",$data->id)?>
	<?php echo Forms::hidden("m",$module)?>
	<?php echo Forms::hidden("type",2)?>
	<?php echo Forms::hidden("t","save")?>
            
          </form>
            
        	</div><!--fine #maschera-form-->
            
            
        
        <span class="clear"></span>
        
        <span class="spazio-10"></span>
        
        
     </div>