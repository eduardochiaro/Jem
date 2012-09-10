<?php

if($data->id){
	$name="modifica mittente '".$data->title."'";
}else{
	$name="inserisci mittente";
}
?><div id="bordo"> <!--inizio #bordo-->
     
     	<h2 class="titolo">Gestione Mittenti Newsletter - <?php echo $name?></h2>
        
        <div id="maschera-form"> <!--inizio #maschera-form-->
        
        	<form action="<?php echo Utils::makeLink($module,"saveSender")?>" enctype="multipart/form-data" method="post" class="form-generico">
 
                <fieldset>

                    <div class="spazio-20"></div>

                    
                    <p class="lungo-1">
                    	<?php echo Forms::element("Mittente","title",Forms::text("title",$data->title,"lungo-1"),"generica");?>
                    </p>
                    
                    <p class="lungo-1">
                    	<?php echo Forms::element("Email mittente","email",Forms::text("email",$data->email,"lungo-1"),"generica");?>
                    </p>
                 
                    
                    <span class="clear spazio-10"></span>
                    
                    <p class="pulsante">
						<?php echo Forms::button("invia modifica","submit",'pulsante-invia')?>
                  	</p>
                    
              </fieldset>
	<?php echo Forms::hidden("id",$data->id)?>
	<?php echo Forms::hidden("m",$module)?>
	<?php echo Forms::hidden("t","saveSender")?>
            
          </form>
            
        	</div><!--fine #maschera-form-->
            
            
        
        <span class="clear"></span>
        
        <span class="spazio-10"></span>
        
        
     </div>