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


$elements = $workspace->get("elements");
$opzioni_e = array();
$nlist = array();
if(is_object($elements)){
	$nlist[] = $elements;
	$elements = $nlist;
}

foreach($elements as $elem){
	$opzioni_e[(string)$elem] = $workspace->loadHandler($elem, "getLatest");
}

if($data->id){
	$name="modifica newsletter '".$data->title."'";
}else{
	$name="inserisci newsletter";
}
?><div id="bordo"> <!--inizio #bordo-->
     
     	<h2 class="titolo">Gestione Newsletter - <?php echo $name?></h2>
        
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
                 
                    <div class="spazio-20"></div>

            <h3>seleziona elementi da inserire</h3>
                    <div class="spazio-20"></div>
			<?php foreach($opzioni_e as $key => $value):?>
				<?php if(is_array($value)):?>
                <div class="column">
                    <h4><?php echo $key?></h4>
                    <div class="spazio-10"></div>
                    <ul>
                        <?php foreach($value as $elem):?>
                        <?php $elementi_attivi = $data->getParameter("elements");
                        if(!is_array($elementi_attivi)) $elementi_attivi = array();
                        ?>
                        <?php $ischeck = (in_array($key . "-" . $elem->id, $elementi_attivi))?$key . "-" . $elem->id:false; ?>
                        <li><?php echo Forms::checkbox("elements-to-insert[]",$key . "-" . $elem->id, $ischeck, 'elements-to-insert campi-radio');?> <?php echo $elem->title?></li>
                        <?php endforeach;?>
                    </ul>
                </div>
                <?php endif;?>
            <?php endforeach;?>
                    <div class="clear spazio-20"></div>
                <p class="lungo-1">
						<?php echo Forms::element("lingua elementi","language",Forms::select("language",$opzioni_l,'',"media",''),"generica")?>
                    </p>
                    <div class="clear spazio-20"></div>
                <p class="lungo-1">
                    	<?php echo Forms::element("testo header","toptext",Forms::textarea("toptext",5,10,$data->toptext,"editor-small"),"generica");?>
                    </p>
                <p class="lungo-1">
                    	<?php echo Forms::element("testo footer","bottomtext",Forms::textarea("bottomtext",5,10,$data->bottomtext,"editor-small"),"generica");?>
                    </p>
                    
                    <div class="clear spazio-20"></div>
                    <h3>preview <?php echo Forms::button("aggiorna preview","button",'','id="sendelements"')?></h3>
                    <div class="clear spazio-10"></div>
                    <div class="cont-mail" id="preview-mail">
                <?php echo $data->text?>
                </div>
                    
                    
                    <span class="clear spazio-10"></span>
                    
                    <p class="pulsante">
						<?php echo Forms::button("esci","button",'pulsante-invia','onclick="location.href=\''.Utils::makeLink($module).'\'"')?> 
						<?php echo Forms::button("salva","submit",'pulsante-invia')?> 
                  	</p>
                    
              </fieldset>
	<?php echo Forms::hidden("id",$data->id)?>
	<?php echo Forms::hidden("m",$module)?>
	<?php echo Forms::hidden("type",1)?>
	<?php echo Forms::hidden("t","save")?>
            
          </form>
            
        	</div><!--fine #maschera-form-->
            
            
        
        <span class="clear"></span>
        
        <span class="spazio-10"></span>
        
        
     </div>