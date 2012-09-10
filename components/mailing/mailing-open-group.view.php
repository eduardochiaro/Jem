<?php
$users = $workspace->get("mailing_users");
$intersect = $workspace->get("mailing_intersect");
$n_intersect = array();
if(is_array($intersect)){
	foreach($intersect as $int){
		$n_intersect[] = $int->user;
	}
}



$n_load = array();

$link=$users->getLinks();
$user=$users->getElements();

if($data->id){
	$name="modifica gruppo '".$data->title."'";
}else{
	$name="inserisci gruppo";
}
?><div id="bordo"> <!--inizio #bordo-->
     
     	<h2 class="titolo">Gestione Gruppi Iscritti - <?php echo $name?></h2>
        
        <div id="maschera-form"> <!--inizio #maschera-form-->
        
        	<form action="<?php echo Utils::makeLink($module,"saveGroup")?>" enctype="multipart/form-data" method="post" class="form-generico">
 
                <fieldset>

                    <div class="spazio-20"></div>

      
                    <p class="lungo-1">
                    	<?php echo Forms::element("Titolo","title",Forms::text("title",$data->title,"lungo-1"),"generica");?>
                    </p>
                    <span class="clear spazio-10"></span>
                    
                    <p class="pulsante">
						<?php echo Forms::button("invia modifica","submit",'pulsante-invia')?>
                  	</p>
                    
              </fieldset>
	<?php echo Forms::hidden("id",$data->id)?>
	<?php echo Forms::hidden("m",$module)?>
	<?php echo Forms::hidden("t","saveGroup")?>
            
          </form>
		<form action="<?php echo Utils::siteOptions("backend")?>" method="get" id="form-search-group-user" class="form-generico">
 
                <fieldset>

                    <?php if($data->id):?>
                   <div class="spazio-20"></div>
                   <div id="ok-load"></div>
                    
               <div id="table-results">
               <h3>ricerca</h3>
                    <p class="flottante-corto-1">
						<?php echo Forms::text("s-search",$workspace->getRequest("s-search"),"lungo");?>
                    </p>
					<p class="pulsante-senza">
					<?php echo Forms::button("cerca","submit",'pulsante-invia-corto')?><span style="display:block; width:10px; height:10px; float:left;"></span><?php echo Forms::button("reset","button",'pulsante-invia-corto','id="reset-usergroup"')?></p>
                   <div class="clear spazio-20"></div>
                <table cellpadding="0" cellspacing="0">
                <tr>
                <th>categoria</th>
                <th>email</th>
                <th>nominativo</th>
                <th class="ultimo" colspan="2"></th>
                </tr>
                
                <?php foreach($user as $row):?>
                <?php
				$check = (in_array($row->id,$n_intersect))? $row->id : false;
				?>
                <tr>
                <td class="small">
                <?php if($check):?>
				<?php echo Forms::button("rimuovi","button","removeUser",'id="chekuser-'.$row->id.'" rel="'.$row->id.'"')?>
                <?php else:?>
				<?php echo Forms::button("aggiungi","button","submitUser",'id="chekuser-'.$row->id.'" rel="'.$row->id.'"')?>
                <?php endif;?>
                </td>
                <td class="medium tleft"><?php echo $row->email?></td>
                <td class="tleft"><?php echo $row->name?> <?php echo $row->surname?></td>
                </tr>
                <?php endforeach;?>
                </table>
                     
            </div>
			<?php echo Utils::makePagination($link)?>

                <?php endif;?>
                    
	<?php echo Forms::hidden("id",$data->id)?>
	<?php echo Forms::hidden("m",$module)?>
	<?php echo Forms::hidden("t","openGroup")?>
            </fieldset>
            </form>
        	</div><!--fine #maschera-form-->
            
            
        
        <span class="clear"></span>
        
        <span class="spazio-10"></span>
        
        
     </div>