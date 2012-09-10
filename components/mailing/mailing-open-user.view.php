<?php
$translations = $workspace->getTranslation();

if($data->id){
	$name="modifica iscritto '".$data->name." ".$data->surname."'";
}else{
	$name="inserisci iscritto";
}
?><div id="bordo"> <!--inizio #bordo-->
     
     	<h2 class="titolo">Gestione Iscritti Newsletter + Mailinglist - <?php echo $name?></h2>
        
        <div id="maschera-form"> <!--inizio #maschera-form-->
        
        	<form action="<?php echo Utils::makeLink($module,"saveUser")?>" enctype="multipart/form-data" method="post" class="form-generico">
 
                <fieldset>

                    <div class="spazio-20"></div>

 					<p class="flottante-lungo">
                    	<?php echo Forms::element("Nome","name",Forms::text("name",$data->name,"lungo"),"generica");?>
                    </p>
                    
                    <p class="flottante-lungo">
                    	<?php echo Forms::element("Cognome","surname",Forms::text("surname",$data->surname,"lungo"),"generica");?>
                    </p>
                    
                    <span class="clear spazio-10"></span>
                    
                    <p class="lungo-1">
                    	<?php echo Forms::element("Email","email",Forms::text("email",$data->email,"lungo-1"),"generica");?>
                    </p>
<?php if($data->getParameters()):?>            
				<div id="table-scheda">

				<table cellpadding="0" cellspacing="0">
   <?php
				foreach($data->getParameters() as $key => $value):
					if($data->getStardard($key)):
				  ?>
                  <tr>
                    <th><?php echo $data->getStardard($key)?></th>
                    <td><?php echo $value?></td>
                  </tr>
				<?php
					endif;	
				endforeach;	
				?>     
                </table>
                </div>    
<?php endif;?>
                    <p class="pulsante">
						<?php echo Forms::button("invia modifica","submit",'pulsante-invia')?>
                  	</p>
                    
              </fieldset>
	<?php echo Forms::hidden("id",$data->id)?>
	<?php echo Forms::hidden("m",$module)?>
	<?php echo Forms::hidden("t","saveUser")?>
            
          </form>
            
        	</div><!--fine #maschera-form-->
            
            
        
        <span class="clear"></span>
        
        <span class="spazio-10"></span>
        
        
     </div>