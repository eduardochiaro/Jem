<?php
$toModel = $workspace->get("toModel");
$actualModel = $workspace->getRequest("limitation");

$link=$data->getLinks();
$data=$data->getElements();
$totalrows=count($data);
$num=1;
?>
<div id="bordo"> <!--inizio #bordo-->
     
     	<h2 class="titolo">Gestione <?php echo $toModel->$actualModel?></h2>
        
<div id="maschera-form"> <!--inizio #maschera-form-->
<form action="<?php echo Utils::siteOptions("backend")?>" method="get" class="form-generico">
<fieldset>
<p class="flottante-lungo">
<?php echo Forms::element("ricerca","s-title",Forms::text("s-title",$workspace->getGet("s-title"),"lungo-2",'maxlength="220" size="20"'),"generica");?>

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
            <div id="inserisci-elemento">
            
            	<a href="<?php echo Utils::makeLink($module,"new",array("limitation"=>$workspace->getRequest("limitation")))?>" title="">inserisci <?php echo $toModel->$actualModel?></a>
                
                <span class="clear"></span>
            
            </div>         

            <div id="table-results">
<table cellpadding="0" cellspacing="0">
<tr>
<th></th>
<th>categoria</th>
<th>titolo</th>

<th class="ultimo" colspan="3"></th>
</tr>

<?php foreach($data as $row):?>
<?php	$validate = ($row->publish)?0:1;
		$validate_text=($row->publish)?"pubblicato":"nascosto";
		$validate_class=($row->publish)?"button green":"button";
		$category = $workspace->loadHandler("categories","exec", $row->category);

?>
<tr>
<td class="small">
<?php echo Utils::href(Utils::makeLink($module,"open",array("id"=>$row->id)),Utils::scaleImage($row->media,100,80,0,0,"immagine"));?>
</td>

<td class="small"><?php echo $category->title?></td>
<td class="tleft"><?php echo $row->title?></td>

		<td class="small">
		<?php echo Utils::href(Utils::makeLink($module,"validate",array("id"=>$row->id,"validate"=>$validate)),$validate_text,array("class"=>$validate_class));?>
		</td>
<td class="small">
		<?php echo Utils::href(Utils::makeLink($module,"open",array("id"=>$row->id)),'modifica',array("class"=>"button"));?>
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