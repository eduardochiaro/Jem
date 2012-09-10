<?php

$status = $workspace->get("status");
$translations = $workspace->getTranslation();
?><div id="bordo"> <!--inizio #bordo-->
     
     	<h2 class="titolo">Archivio Curriculums - scheda di <?php echo $data->getParameter("name"). " " .$data->getParameter("surname")?></h2>
        
        <div id="maschera-form"> <!--inizio #maschera-form-->
        
        	<form action="<?php echo Utils::makeLink($module,"save")?>" enctype="multipart/form-data" method="post" class="form-generico">
 
                <fieldset>
                            <div id="table-scheda">

				<table cellpadding="0" cellspacing="0">
                <?php 
		$job = $workspace->loadHandler("jobs","exec", $data->job);?>
                  <tr>
                    <th>lavoro</th>
                    <td><?php echo $job->title?></td>
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
                <?php if($data->getParameter("cv")):?>
                <tr>
                    <th>CV</th>
                 	<td><?php echo Utils::href(Utils::siteOptions("siteurl")."uploads/frontend/".$data->getParameter("cv"),'scarica curriculum'); ?></td>   
				 </tr>
				<?php endif;?>
                  <tr>
                    <th>stato</th>
                 	<td><?php echo $status[$data->status]?></td>   
				 </tr>

                </table>

</div>
                 </fieldset>           
                    <span class="clear spazio-10"></span>
                    
                    <p class="pulsante">
						
                <a href="javascript: window.print()" title="stampa" class="button">stampa</a>
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