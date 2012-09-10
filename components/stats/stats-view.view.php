<?php
$contacts = $workspace->get("StatsContacts");


    $mesi = array (
                   "0" => "Null",
                   "1" => "Gennaio",
                   "2" => "Febbraio",
                   "3" => "Marzo",
                   "4" => "Aprile",
                   "5" => "Maggio",
                   "6" => "Giugno",
                   "7" => "Luglio",
                   "8" => "Agosto",
                   "9" => "Settembre",
                   "10" => "Ottobre",
                   "11" => "Novembre",
                   "12" => "Dicembre"
                  );
?>
        <style type="text/css">
            .bar{height:20px;background:#FF7300;}
        </style>
	<div id="bordo"> <!--inizio #bordo-->
     
     	
        <?php
			if (count($contacts) >= 0){
		?>
        <h2 class="titolo">statistiche contatti anno <?php echo date("Y")?></h2>
		<h2 class="titolo">provenienza regionale</h2>
        <div id="table-results">
        <table cellpadding="0" cellspacing="0">
                  <tr>
                    <th>regione</th>
                    <th>contatti</th>
                    <th>percentuale</th>
                    <th class="ultimo">grafico</th>

              	 </tr>
                 <?php
				 
		  		foreach($workspace->get("StatsContacts-region") as $key => $value):
		  ?>
                 <tr>
                    <td class="tleft medium"><?php if ($key!=''){ echo $key; }else{ echo "Non Definito";}?></td>
                    <td class="small"><?php echo (int)$value;?></td>
                    <td class="small"><?php echo $dimensioni = (round((($value*100)/count($contacts)), 2))."%";?></td>
                    <td style="width:520px"><div class="bar" style="width:<?php echo ((round((($value*100)/count($contacts))))*5)+2; ?>px"></div></td>
                 </tr>
           <?php
		   endforeach;
		   ?>
  
            </table>        
                     
        <span class="clear"></span>
        
        <span class="spazio-10"></span>
        <h2 class="titolo">fasce orarie dei contatti</h2>
        <table cellpadding="0" cellspacing="0">
                  <tr>
                    <th>orario</th>
                    <th>contatti</th>
                    <th>percentuale</th>
                    <th class="ultimo">grafico</th>

              	 </tr>
                 <?php for($i=0;$i<=22;$i=$i+2):
		 				 $conteggio = $workspace->get("StatsContacts-H".$i);?>
                 <tr>
                    <td class="small"><?php echo $i?>.00-<?php echo $i+1?>.59</td>
                    <td class="small"><?php echo (int)$conteggio ?></td>
                   <td class="small"><?php echo $dimensioni=(round((($conteggio*100)/count($contacts)), 2))."%";?></td>
                   <td style="width:520px"><div class="bar" style="width:<?php echo ((round((($conteggio*100)/count($contacts))))*5)+2; ?>px"></div></td>
                 </tr>
                 <?php endfor;?>
           
            </table>
            <span class="clear"></span>
        
        <span class="spazio-10"></span>
        
        <h2 class="titolo">grafico mensile dei contatti</h2>
        <table cellpadding="0" cellspacing="0">
                  <tr>
                    <th>mese</th>
                    <th>contatti</th>
                    <th>percentuale</th>
                    <th class="ultimo">grafico</th>

              	 </tr>
                 <?php
                 for ($i = 1; $i <= date("m"); $i++):
					  $conteggio = $workspace->get("StatsContacts-M".$i);
				?>
                 <tr>
                    <td class="small"><?php echo $mesi[$i] ?></td>
                    <td class="small"><?php echo (int)$conteggio ?></td>
                   <td class="small"><?php echo $dimensioni=(round((($conteggio*100)/count($contacts)), 2))."%";?></td>
                   <td style="width:520px"><div class="bar" style="width:<?php echo ((round((($conteggio*100)/count($contacts))))*5)+2; ?>px"></div></td>
                 </tr>
                 <?php endfor;?>     
                 </table>
        </div><?php }
		else echo "<p style=\"text-align: center; padding: 30px 0; font-weight: bold;\">nessun contattato archiviato</p>";
		 ?> 
</div>