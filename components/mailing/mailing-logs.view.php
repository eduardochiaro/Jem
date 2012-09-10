<?php

$mailing = $workspace->get("mailing");

$opzioni[3] = "tutti";
$opzioni[0] = "non inviato";
$opzioni[1] = "inviato";
$opzioni[2] = "letto";

$link = $data->getLinks();
$data = $data->getElements();
$totalrows=count($data);
$num=1;
?>
<div id="bordo"> <!--inizio #bordo-->
     
     	<h2 class="titolo">Gestione Logs per "<?php echo $mailing->title?>"</h2>
        
<div id="maschera-form"> <!--inizio #maschera-form-->
<form action="<?php echo Utils::siteOptions("backend")?>" method="get" class="form-generico">
<fieldset>
<p class="flottante-lungo">
<?php echo Forms::element("ricerca","s-title",Forms::text("s-title",$workspace->getGet("s-title"),"lungo-2",'maxlength="220" size="20"'),"generica");?>
</p>
<p class="flottante-lungo">
<?php echo Forms::element("stato","s-status",Forms::select("s-status",$opzioni,$workspace->getGet("s-status",3),"lunga",''),"generica");?>
</p>
	<div class="clear"></div>
<p class="pulsante">
<?php echo Forms::button("avvia ricerca","submit",'pulsante-invia')?>
</p>

	<?php echo Forms::hidden("id",$workspace->getRequest("id",3))?>
	<?php echo Forms::hidden("m",$module)?>
	<?php echo Forms::hidden("t",$task)?>
    </fieldset>
	</form>
</div>     
     

<div id="table-results">
<table cellpadding="0" cellspacing="0">
<tr>
<th>email</th>
<th class="ultimo">stato</th>
</tr>

<?php foreach($data as $row):?>
<tr>
<td class="medium tleft"><span<?php if(!$row->status):?> class="red"<?php endif?>><?php echo $row->email?></span></td>
<td class="small"><span<?php if(!$row->status):?> class="red"<?php endif?>><?php echo $opzioni[$row->status]?></span></td>
</tr>
<?php endforeach;?>
</table>
     
</div>
<?php echo Utils::makePagination($link)?>
        
        <span class="clear"></span>
        
        <span class="spazio-10"></span>
        
</div>