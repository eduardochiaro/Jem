<?php

$translations = $workspace->getTranslation();

if($data->id){
	$name="modifica indirizzo '".$data->title."'";
}else{
	$name="inserisci indirizzo";
}
?><div id="bordo"> <!--inizio #bordo-->
     
     	<h2 class="titolo">Gestione Risposte e Inoltro - <?php echo $name?></h2>
        
        <div id="maschera-form"> <!--inizio #maschera-form-->
        
        	<form action="<?php echo Utils::makeLink($module,"save")?>" enctype="multipart/form-data" method="post" class="form-generico">
 
                <fieldset>
                    <div class="spazio-20"></div>
<?php 

	$args = array(
				  array(
						"text"=>"oggetto",
						"type"=>"text",
						"id"=>"oggetto",
						"class"=>"lungo-1",
						"required"=>true,
						"editor"=>false,
						"standard"=>$data->title,
						"posttext"=>"<em>l'oggetto verra applicato all'email che arriva all'amministratore/i settati nei parametri del CM e nella email di autorisposta all'utente. </em>"
						),
				  array(
						"text"=>"risposta automatica",
						"type"=>"textarea",
						"id"=>"text",
						"class"=>"lungo-2",
						"required"=>false,
						"editor"=>false,
						"standard"=>$data->text,
						"posttext"=>"<em>testo dell'email che verra inoltrata all'utente all'atto successivo di invio contatto </em>"
						)
				  );
	$translations->makeTabs($args,$data->getTable(),$data->id);
	
?>       

                   <p class="lungo-1">
                    	<?php echo Forms::element("Email","email",Forms::textarea("email",1,10,$data->email,"lungo-1"),"generica");?>
                        <em>indica i destinatari (anche multipli separati da ;) delle mail di contatto per rispettivo motivo</em>
                    </p>
                 </fieldset>           
                 <fieldset>

  <p class="lungo-1"> 
  <label for="subtitle" class="generica">Stato</label>
  <?php echo Forms::radio("publish","1",$data->publish,'campi-radio','',"1");?><em>online</em>
  <?php echo Forms::radio("publish","0",$data->publish,'campi-radio','',"0");?><em>offline</em>
  </p>   
                 </fieldset>

                    <span class="clear spazio-10"></span>
                    
                    <p class="pulsante">
						<?php echo Forms::button("invia modifica","submit",'pulsante-invia')?>
                  	</p>
                    
              </fieldset>
	<?php echo Forms::hidden("id",$data->id)?>
	<?php echo Forms::hidden("m",$module)?>
	<?php echo Forms::hidden("t","saveReply")?>
            
          </form>
            
        	</div><!--fine #maschera-form-->
            
            
        
        <span class="clear"></span>
        
        <span class="spazio-10"></span>
        
        
     </div>