<?php

$translations = $workspace->getTranslation();
?><div id="bordo"> <!--inizio #bordo-->
     
     	<h2 class="titolo">Archivio Contatti - scheda di <?php echo $data->getParameter("name"). " " .$data->getParameter("surname")?></h2>
        
        <div id="maschera-form"> <!--inizio #maschera-form-->
        
        	<form action="<?php echo Utils::makeLink($module,"save")?>" enctype="multipart/form-data" method="post" class="form-generico">
 
                <fieldset>
                            <div id="table-scheda">

				<table cellpadding="0" cellspacing="0">
                <?php 
		$reply = $workspace->loadHandler("replies","exec", $data->reply);?>
                  <tr>
                    <th>motivo</th>
                    <td><?php echo $reply->title?></td>
                  </tr>
                  <tr>
                    <th>data ricezione</th>
                    <td><?php echo Utils::parseDate($data->created,"%d/%m/%Y %H:%M:%S")?></td>
                  </tr>
                  <tr>
                  <th colspan="2" class="title">dati</th>
                  </tr>
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
                  <tr>
                    <th>stato</th>
                    
                    <td class="arancione"> <p class="lungo-1"> 
  <label for="subtitle" class="generica">Stato</label>
  <?php echo Forms::radio("status",1,$data->status,'campi-radio','',"1");?><em>contattato</em>
  <?php echo Forms::radio("status",0,$data->status,'campi-radio','',"0");?><em>non contattato</em>
  </p>   </td>
                                    </tr>

                </table>

</div>
                 </fieldset>           
                 <fieldset>

 
                 </fieldset>

                    <span class="clear spazio-10"></span>
                    
                    <p class="pulsante">
						
                <a href="javascript: window.print()" title="stampa" class="button">stampa</a><?php echo Forms::button("invia modifica","submit",'pulsante-invia')?>
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