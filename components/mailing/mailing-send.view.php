<?php

$categories = $workspace->get("categories");
$opzioni[""] = "tutte";



$name="Invia '".$data->title."'";
?><div id="bordo"> <!--inizio #bordo-->
     
     	<h2 class="titolo">Pannello Invio Mailing - <?php echo $name?></h2>
        
        <div id="maschera-form"> <!--inizio #maschera-form-->
        
        	<form action="<?php echo Utils::makeLink($module,"sendTo")?>" enctype="multipart/form-data" method="post" class="form-generico">
 
        <span class="spazio-20"></span>
                <fieldset>
                <h3>preview</h3>
        <span class="spazio-20"></span>
                <div class="cont-mail">
                <?php echo $data->text?>
                </div>
                </fieldset>
                
        <span class="spazio-20"></span>
        <span class="spazio-20"></span>
                
                <fieldset>
                
                
                <h3>Invia a</h3>
        <span class="spazio-20"></span>

                   
                <p class="lungo-1">
               <span><?php echo Forms::checkbox("send-all",1 , false, 'select-all campi-radio');?> invia a tutti</span>
                    <span class="clear spazio-10"></span>
               <?php foreach($categories as $category):?>
               <span><?php echo Forms::checkbox("send-to[]",$category->id , false, 'send-to campi-radio');?> <?php echo $category->title?></span>
                    <span class="clear spazio-10"></span>
				<?php endforeach?>
                </p>
                    <span class="clear spazio-10"></span>
                    
                    <p class="pulsante">
			   <?php echo Forms::button("invia","submit",'pulsante-invia')?>
                </p>
                
        <span class="spazio-20"></span>
                <div id="insert-user-mailing">
                
                </div>
                    
              </fieldset>
			<?php echo Forms::hidden("id",$data->id)?>
            <?php echo Forms::hidden("m",$module)?>
            <?php echo Forms::hidden("t","sendTo")?>
            
          </form>
            
        	</div><!--fine #maschera-form-->
            
            
        
        <span class="clear"></span>
        
        <span class="spazio-10"></span>
        
        
     </div>