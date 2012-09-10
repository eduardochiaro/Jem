<?php

$categories = $workspace->get("categories");
$parameters = $workspace->get("parameters");

$opzioni[""] = "tutti";
foreach($categories as $id => $title){
	$opzioni[$id]=$title;
}

$link=$data->getLinks();
$data=$data->getElements();
$totalrows=count($data);
$num=1;
?>
<div id="bordo"> <!--inizio #bordo-->
     
     	<h2 class="titolo">Pannello Invio Mailing</h2>
        
<div id="maschera-form"> <!--inizio #maschera-form-->
<form action="<?php echo Utils::siteOptions("backend")?>" method="get" class="form-generico">
<fieldset>
<p class="flottante-lungo">
<?php echo Forms::element("ricerca","s-title",Forms::text("s-title",$workspace->getGet("s-title"),"lungo-2",'maxlength="220" size="20"'),"generica");?>
</p>
<p class="flottante-lungo">
<?php echo Forms::element("tipologia","s-category",Forms::select("s-category",$opzioni,$workspace->getGet("s-category"),"lunga",''),"generica");?>
</p>
	<div class="clear"></div>
<p class="pulsante">
<?php echo Forms::button("avvia ricerca","submit",'pulsante-invia')?>
</p>

	<?php echo Forms::hidden("m",$module)?>
	<?php echo Forms::hidden("t",$task)?>
    </fieldset>
	</form>
</div>     
            <div id="inserisci-elemento">
            	<?php if((string)$parameters->mailinglist):?><a href="<?php echo Utils::makeLink($module,"new",array("type"=>2))?>" title="">inserisci mailinglist</a><?php endif?>
            	<?php if((string)$parameters->newsletter):?><a href="<?php echo Utils::makeLink($module,"new",array("type"=>1))?>" title="">inserisci newsletter</a><?php endif?>
            
                
                <span class="clear"></span>
            
            </div>         

            <div id="table-results">
<table cellpadding="0" cellspacing="0">
<tr>
<th>tipologia</th>
<th>titolo</th>
<th>creata</th>
<th>inviata</th>

<th class="ultimo" colspan="3"></th>
</tr>

<?php foreach($data as $row):?>
<?php	$validate = ($row->publish)?0:1;
		$validate_text=($row->publish)?"pubblicato":"nascosto";
		$validate_class=($row->publish)?"button green":"button";
		?>
<tr>
<td class="medium"><?php echo $opzioni[$row->type]?></td>
<td class="tleft"><?php echo $row->title?></td>
<td class="small"><?php echo Utils::parseDate($row->created)?></td>
<td class="medium"><?php 
switch($row->sent){
	case 0:
		echo '<span class="red">mai inviata</span>';
		break;
	case 1:
		echo '<span class="red">invio non completato</span>';
		break;
	case 2:
		echo Utils::parseDate($row->getParameter("lastsent"),"%d/%m/%Y %H:%M:%S");
		break;
}
?></td>
<td class="small">
<?php 
switch($row->sent){
	case 0:
		echo Utils::href(Utils::makeLink($module,"send",array("id"=>$row->id)),'invia',array("class"=>"button green"));
		break;
	case 1:
		echo Utils::href(Utils::makeLink($module,"send",array("id"=>$row->id)),'continua',array("class"=>"button green"));
		break;
	case 2:
		echo Utils::href(Utils::makeLink($module,"send",array("id"=>$row->id)),'continua',array("class"=>"button green"));

		if($row->type == 2) echo Utils::href(Utils::makeLink($module,"duplicate",array("id"=>$row->id)),'duplica',array("class"=>"button green","onclick"=>"return confirm('sei sicuro di voler duplicare questa newsletter/mailinglist');"));
		break;
}
?>
		</td>
<td class="small">
<?php 
if($row->sent == 0){ 
	echo Utils::href(Utils::makeLink($module,"open",array("id"=>$row->id)),'modifica',array("class"=>"button"));
}
if($row->sent == 2){ 
	echo Utils::href(Utils::makeLink($module,"logs",array("id"=>$row->id)),'logs',array("class"=>"green button"));
}?>
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