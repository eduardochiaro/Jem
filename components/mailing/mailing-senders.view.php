<?php
$link=$data->getLinks();
$data=$data->getElements();
$totalrows=count($data);
$num=1;
?>
<div id="bordo"> <!--inizio #bordo-->
     
     	<h2 class="titolo">Gestione Mittenti Newsletter</h2>
        
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

	<?php echo Forms::hidden("m",$module)?>
	<?php echo Forms::hidden("t",$task)?>
    </fieldset>
	</form>
</div>     
            <div id="inserisci-elemento">
            
            	<a href="<?php echo Utils::makeLink($module,"newSender")?>" title="">inserisci mittente</a>
                
                <span class="clear"></span>
            
            </div>         

            <div id="table-results">
<table cellpadding="0" cellspacing="0">
<tr>
<th>mittente</th>
<th>email</th>

<th class="ultimo" colspan="2"></th>
</tr>

<?php foreach($data as $row):?>
<tr>
<td class="medium"><?php echo $row->title?></td>
<td class="tleft"><?php echo $row->email?></td>
<td class="small">
		<?php echo Utils::href(Utils::makeLink($module,"openSender",array("id"=>$row->id)),'modifica',array("class"=>"button"));?>
		</td>

    <td class="small ultimo">
    <?php 
        echo Utils::href(Utils::makeLink($module,"deleteSender",array("id"=>$row->id)),"cancella",array("onclick"=>'return confirm(\'Sei sicuro di voler eliminare questo elemento?\')',"class"=>"button red"));
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