<?php
$components = $workspace->get("componentsList");
$controller = $workspace->get('controllers');

if($data->id){
	$name="modifica utente '".$data->username."'";
}else{
	$name="inserisci utente";
}
?><div id="bordo"> <!--inizio #bordo-->
     
     	<h2 class="titolo">Gestione utenti - <?php echo $name?></h2>
        
        <div id="maschera-form"> <!--inizio #maschera-form-->
        
        	<form action="<?php echo Utils::makeLink($module,"save")?>" method="post" class="form-generico">
 
                <fieldset>
                
                    
                    <p class="lungo-1">
                    	<?php echo Forms::element("username","username",Forms::text("username",$data->username,"lungo-2",'maxlength="220" size="20"'),"generica");?>
                    </p>
                    <p class="flottante-lungo">
                    	<?php echo Forms::element("password","password",Forms::password("password",'',"lungo-2",'maxlength="220" size="20"'),"generica");?>
                    </p>
                    
                    <p class="flottante-lungo">
                    	<?php echo Forms::element("conferma password","conf-password",Forms::password("conf-password",'',"lungo-2",'maxlength="220" size="20"'),"generica");?>
                    </p>
                    <?php
					if(!$my->root){
						echo  Forms::hidden("root",$data->root);
					}else{
					?>  
						 <p class="lungo-1">
                        
                    	<?php echo Forms::element("utente root","root",Forms::select("root",array("no","si"),$data->root,"numero-corto"),"generica");?>
					  </p>
					  <?php }?>





               
              </fieldset>
              
              <fieldset>
              <h2>Gestione Permessi</h2>
 
<?php 

$mypermits=unserialize($my->permits);
if(!$mypermits) $mypermits = array();
foreach($components as $row):?>
              <?php if($row->required == false):?>
	<?php if(array_key_exists((string)$row->title, $controller)  && (in_array((string)$row->title, $mypermits) || $my->root )):?>
    <?php $star = ($data->getPermission($row->title))?$row->title:""?>
             <p class="flottante-lungo">
             <?php echo Forms::checkbox("permits[]",$row->title,$star)?>
            <?php echo Forms::element($row->explain,$row->title,"","checkbox");?>
          </p>
    <?php endif;?>
<?php endif;?>
<?php endforeach;?>
              
              </fieldset>
                    
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