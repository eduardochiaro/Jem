<?php
$link=$data->getLinks();
$data=$data->getElements();
$totalrows=count($data);
$num=1;
?>
<div id="bordo"> <!--inizio #bordo-->
     
     	<h2 class="titolo">Gestione Utenti Amministrativi</h2>
        
<div id="maschera-form"> <!--inizio #maschera-form-->
<form action="<?php echo Utils::siteOptions("backend")?>" method="get" class="form-generico">
<fieldset>
<p class="flottante-lungo">
<?php echo Forms::element("username","s-username",Forms::text("s-username",$workspace->getGet("s-username"),"lungo",'maxlength="220" size="20"'),"generica");?>

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
            
            	<a href="<?php echo Utils::makeLink($module,"new")?>" title="">inserisci utente</a>
                
                <span class="clear"></span>
            
            </div>         

            <div id="table-results">
<table cellpadding="0" cellspacing="0">
<tr>
<th>username</th>

<th class="ultimo" colspan="2"></th>
</tr>

<?php foreach($data as $row):?>
<tr>
<td class="tleft"><?php echo $row->username?></td>
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