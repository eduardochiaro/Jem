<?php
$translations = $workspace->getTranslation();

$categories = $workspace->get("categories");
$opzioni[""] = " - nessuna - ";
foreach($categories as $cat){
	$opzioni[$cat->id] = $cat->title;
}

?><div id="bordo"> <!--inizio #bordo-->
     
     	<h2 class="titolo">Gestione Iscritti Newsletter + Mailinglist - carica da CSV</h2>
        
        <div id="maschera-form"> <!--inizio #maschera-form-->
        
        	<form action="<?php echo Utils::makeLink($module,"loadCsv")?>" enctype="multipart/form-data" method="post" class="form-generico">
 
                <fieldset>

                    <div class="spazio-20"></div>

 					<p class="flottante-lungo">
                    
               	     <?php echo Forms::element("Associa a questa categoria","category",Forms::select("category", $opzioni, $workspace->getGet("category"),"lunga",''),"generica");?>
                    </p>
 					<p class="flottante-lungo">
                    	<?php echo Forms::element("carica file .csv","csv",Forms::file("csv","lungo"),"generica");?>
                    </p>
                    
                    
                    <div class="clear spazio-20"></div>

                    <p class="pulsante">
						<?php echo Forms::button("carica","submit",'pulsante-invia')?>
                  	</p>
                  
                    
              </fieldset>
	<?php echo Forms::hidden("id",$data->id)?>
	<?php echo Forms::hidden("m",$module)?>
	<?php echo Forms::hidden("t","loadCsv")?>
            
          </form>
            
        	</div><!--fine #maschera-form-->
            
            
        
        <span class="clear"></span>
        
        <span class="spazio-10"></span>
        
        
     </div>