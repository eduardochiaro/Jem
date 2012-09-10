<?php
$translations = $workspace->getTranslation();

if($data->id){
	$name="modifica template '".$data->title."'";
}else{
	$name="inserisci template";
}
?><div id="bordo"> <!--inizio #bordo-->
     
     	<h2 class="titolo">Gestione Template Newsletter - <?php echo $name?></h2>
        
        <div id="maschera-form"> <!--inizio #maschera-form-->
        
        	<form action="<?php echo Utils::makeLink($module,"saveTemplate")?>" enctype="multipart/form-data" method="post" class="form-generico">
 
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
						"text"=>"descrizione",
						"type"=>"textarea",
						"id"=>"text",
						"required"=>false,
						"editor-full"=>true,
						"standard"=>$data->text
						)
				  );
	$translations->makeTabs($args,$data->getTable(),$data->id);
?>
<span class="clear spazio-20"></span>
<p><strong>%USER%</strong> inserisce il nome e cognome dell'iscritto a cui verr&agrave; inviata</p>
<span class="clear spazio-10"></span>
<p><strong>%UNSUBSCRIBE%</strong> link per disiscriversi dal sistema. Inserire come href di un tag &lt;a&gt;. Se non è presente verrà inserito uno link standard durante l'invio</p>
<span class="clear spazio-10"></span>
<p><strong>%VIEW%</strong> link per visualizzare quest l'email nel browser. Inserire come href di un tag &lt;a&gt;. Se non è presente verrà inserito uno link standard durante l'invio</p>
                    
                    <span class="clear spazio-10"></span>
                    
                    <p class="pulsante">
						<?php echo Forms::button("invia modifica","submit",'pulsante-invia')?>
                  	</p>
                    
              </fieldset>
	<?php echo Forms::hidden("id",$data->id)?>
	<?php echo Forms::hidden("m",$module)?>
	<?php echo Forms::hidden("t","saveTemplate")?>
            
          </form>
            
        	</div><!--fine #maschera-form-->
            
            
        
        <span class="clear"></span>
        
        <span class="spazio-10"></span>
        
        
     </div>