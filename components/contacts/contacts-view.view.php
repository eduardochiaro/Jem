<?php
$opzioni= array(""=>"tutti");
$categories = $workspace->get("replies");
foreach($categories as $category){
	$opzioni[$category->id]=$category->title;
}

$status = $workspace->get("status");
$opzioni_status= array(""=>"tutti");
foreach($status as $key => $value){
	$opzioni_status[$key]=$value;
}

$link=$data->getLinks();
$data=$data->getElements();
$totalrows=count($data);
$num=1;
?>
<div id="bordo"> <!--inizio #bordo-->
     
     	<h2 class="titolo">Archivio Contatti</h2>
        
<div id="maschera-form"> <!--inizio #maschera-form-->
<form action="<?php echo Utils::siteOptions("backend")?>" method="get" class="form-generico">
<fieldset>
<p class="flottante-lungo">
<?php echo Forms::element("ricerca","s-title",Forms::text("s-title",$workspace->getGet("s-title"),"lungo-2",'maxlength="220" size="20"'),"generica");?>
</p>
<p class="flottante-lungo">
        <?php echo Forms::element("motivo","s-reply",Forms::select("s-reply", $opzioni, $workspace->getGet("s-reply"),"lunga",''),"generica");?>
</p>
<p class="flottante-lungo">
        <?php echo Forms::element("stato","s-status",Forms::select("s-status", $opzioni_status, $workspace->getGet("s-status"),"lunga",''),"generica");?>
</p>

	<div class="clear"></div>
<p class="pulsante">
<?php echo Forms::button("avvia ricerca","submit",'pulsante-invia')?>
</p>

	<?php echo Forms::hidden("model",$actualModel)?>
	<?php echo Forms::hidden("m",$module)?>
	<?php echo Forms::hidden("t",$task)?>
    </fieldset>
	</form>
</div>     

            <div id="table-results">
<table cellpadding="0" cellspacing="0">
<tr>
<th>motivo</th>
<th>email</th>
<th>contatto</th>

<th>data</th>
<th class="ultimo" colspan="3"></th>
</tr>

<?php foreach($data as $row):?>
<?php	$status = ($row->status)?0:1;
		$status_text = ($row->status)?"contattato":"non contattato";
		$status_class = ($row->status)?"button green":"button";
		$reply = $workspace->loadHandler("replies","exec", $row->reply);
		
		?>
<tr>
<td class="medium"><?php echo $reply->title?></td>

<td class="medium"><?php echo $row->getParameter("email")?></td>
<td class="tleft"><?php echo $row->getParameter("name"). " " .$row->getParameter("surname")?></td>
<td class="medium"><?php echo Utils::parseDate($row->created,"%d/%m/%Y %H:%M")?></td>

		<td class="medium">
        <?php echo Utils::href(Utils::makeLink($module,"status",array("id"=>$row->id,"status"=>$status)),$status_text,array("class"=>$status_class));?>

		</td>
<td class="small">
		<?php echo Utils::href(Utils::makeLink($module,"open",array("id"=>$row->id)),'apri',array("class"=>"button"));?>
		</td>
		<td class="small ultimo">
		<?php 
			echo Utils::href(Utils::makeLink($module,"delete",array("id"=>$row->id)),"cancella",array("onclick"=>'return confirm(\'Sei sicuro di voler eliminare questo elemento?\')',"class"=>"button red"));
		?>
		</td>
</tr>
<?php endforeach;?>
</table>
     
            </div>
<?php echo Utils::makePagination($link)?>
        
        <span class="clear"></span>
        
        <span class="spazio-10"></span>
        
</div>