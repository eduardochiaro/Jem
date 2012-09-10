<?php
$components = $workspace->get("componentsList");
?><div id="bordo"> <!--inizio #bordo-->
     
     	<h2 class="titolo">Gestione Componenti</h2>
    
   

            <div id="table-results">
<table cellpadding="0" cellspacing="0">
<tr>
<th>componente</th>
<th>descrizione</th>
<th class="ultimo"></th>
</tr>

<?php foreach($data as $row):?>
<?php if(isset($row->title)):?>
<tr>
<td class="small tleft"><?php echo $row->title?></td>
<td class="tleft"><?php echo $row->description?></td>
<td class="small ultimo">
<?php if($row->required == false):?>
	<?php if(array_key_exists((string)$row->title, $components)):?>
     <?php echo Utils::href(Utils::makeLink($module,"compSave",array("add"=>0,"id"=>$row->title)),"attivo",array("class"=>"button green"))?>
    <?php else:?>
     <?php echo Utils::href(Utils::makeLink($module,"compSave",array("add"=>1,"id"=>$row->title)),"disattivo",array("class"=>"button grey"))?>
    <?php endif;?>
<?php endif;?>
</td>
</tr>
<?php endif;?>
<?php endforeach;?>
</table>
     
            </div>
        
        <span class="clear"></span>
        
        <span class="spazio-10"></span>
        
</div>