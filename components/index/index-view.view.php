<?php
$contacts = $workspace->get("DashContacts");
$contactsSD = $workspace->get("DashContactsStatsToday");
$contactsSW = $workspace->get("DashContactsStatsWeek");
$contactsSM = $workspace->get("DashContactsStatsMonth");
$contactsSY = $workspace->get("DashContactsStatsYear");
$config = $workspace->get("DashConfig");
$news = $workspace->get("DashNews");
$stats = $workspace->get("DashStats");


?>

<div id="bordo"> <!--inizio #bordo-->

     	<div id="colonna-sx"> 

			<?php if($contacts):?>
        	<div class="box-dashboard">
            
            	<h2 class="contatti-recenti">contatti recenti</h2>


				<?php if(!count($contacts)):?>
                	<div class="contatto">nessun contatto archiviato</div>
                
                <?php endif;?>
                <?php foreach($contacts as $row):?>
                <div class="contatto">
                
                	<div><strong><?php echo Utils::href(Utils::makeLink("contacts","open",array("id"=>$row->id)),$row->getParameter("name") . " " . $row->getParameter("surname"));?></strong> - <a href="mailto:<?php echo $row->getParameter("email")?>" title="<?php echo $row->getParameter("email")?>" class="titolo"><?php echo $row->getParameter("email")?></a></div>
                   
                    <?php echo Utils::href(Utils::makeLink("contacts","open",array("id"=>$row->id)),Utils::cutText($row->getParameter("message"),50));?>
                
                </div>
				<?php endforeach;?>

                
             
            </div>
            
            <div class="box-dashboard">
            
            	<h2 class="statistiche-contatti">statistiche contatti</h2>
                
                <table cellpadding="0" cellspacing="0">
                
                      <tr>
                        <th>oggi</th>
                        <th>questa settimana</th>
                        <th>questo mese</th>
                        <th class="ultimo">quest'anno</th>
                      </tr>
                      <tr>
                        <td><?php echo count($contactsSD )?></td>
                        <td><?php echo count($contactsSW )?></td>
                        <td><?php echo count($contactsSM )?></td>
                        <td class="ultimo"><?php echo count($contactsSY )?></td>
                      </tr>
                  
            	</table>

            </div>
            <?php endif?>
            
          </div>
        
        <div id="colonna-dx"> 
        
			<?php if($news):?>
        	<div class="box-dashboard"> 
            
            	<h2 class="news-recenti">news recenti</h2>
			<?php if(!count($news)):?>
            <p>nessuna news archiviata</p>
                
                <?php endif;?>
                <?php foreach($news as $row):?>


            	<div class="news">
                
                	<p class="blu"><?php echo Utils::parseDate($row->pubdate)?></p>
                    <p><a href="<?php echo Utils::makeLink("news","open",array("id"=>$row->id))?>" title="" class="blu"><?php echo $row->title?></a></p>
                    <p><a href="<?php echo Utils::makeLink("news","open",array("id"=>$row->id))?>" title=""><?php echo Utils::cutText($row->pretext,100)?></a></p>
                
                </div>
				<?php endforeach;?>

                
            
            </div>
            <?php endif?>
			<?php if($stats):?>
            
            <div class="box-dashboard"> 
            
            	<h2 class="statistiche">statistiche del sito</h2>
                
                <div class="google"><a href="<?php echo Utils::makeLink("stats","google")?>" title="google analytics">google analytics</a></div>
            
            	<div class="dati">
                
                	<p class="blu">la tua mail</p>
                    <p class="grigio"><a href="<?php echo Utils::makeLink("stats","google")?>"><?php echo $config->analytics_username?></a></p>
                <span class="spazio-10"></span>
                    <p class="blu">&nbsp;</p>
              </div>
                
                <span class="clear"></span>
            
            </div> 
            <?php endif?>
            
            <div class="box-dashboard"> 
            
            	<h2 class="esportazione">esportazione del database</h2>
                
                <p>Clicca sul pulsante qui sotto per creare un file archivio contenente il database di questo sito.</p>
                
                <span class="spazio-20"></span>
                
                <a href="<?php echo Utils::makeLink("config","backup")?>" title="esporta il database" class="esporta">esporta il database</a>
                
                <span class="clear spazio-10"></span>
            
            </div> 
        
        </div> 

        
        <span class="clear"></span>
     
     </div>