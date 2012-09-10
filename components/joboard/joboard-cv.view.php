<?php
$categories = $workspace->get("job");
$opzioni= array(0=>"tutte");
foreach($categories as $category){
	$opzioni[$category->id]=$category->title;
}

$status = $workspace->get("status");
$opzioni_status= array(0=>"tutte");
foreach($status as $key => $value){
	$opzioni_status[$key]=$value;
}

$link=$data->getLinks();
$data=$data->getElements();
$totalrows=count($data);
$num=1;
?>
<div id="bordo"> <!--inizio #bordo-->
     
     	<h2 class="titolo">Archivio Curriculums</h2>
        
<div id="maschera-form"> <!--inizio #maschera-form-->
<form action="<?php echo Utils::siteOptions("backend")?>" method="get" class="form-generico">
<fieldset>
<p class="flottante-lungo">
<?php echo Forms::element("ricerca","s-title",Forms::text("s-title",$workspace->getGet("s-title"),"lungo-2",'maxlength="220" size="20"'),"generica");?>
</p>
<p class="flottante-lungo">
<?php echo Forms::element("offerta","s-job",Forms::select("s-job", $opzioni, $workspace->getGet("s-job"),"lunga",''),"generica");?>
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
<th>offerta</th>
<th>email</th>
<th>contatto</th>
<th>telefono</th>

<th>data</th>
<th class="ultimo" colspan="3"></th>
</tr>
<?php unset($opzioni_status[0]);?>
<?php foreach($data as $row):?>
<?php	$status = ($row->status)?0:1;
		$status_text = ($row->status)?"contattato":"non contattato";
		$status_class = ($row->status)?"button green":"button";
		$job = $workspace->loadHandler("jobs","exec", $row->job);
		
		?>
<tr>
<td class="medium"><?php echo $job->title?></td>

<td class="medium"><?php echo $row->getParameter("email")?></td>
<td class="tleft"><?php echo $row->getParameter("name"). " " .$row->getParameter("surname")?></td>
 <?php $text = ($row->getParameter("phone"))? $row->getParameter("phone"): $row->getParameter("mobile")?>
<td class="small"><?php echo $text?></td>
<td class="medium"><?php echo Utils::parseDate($row->created,"%d/%m/%Y %H:%M")?></td>

		<td class="form">

<form class="form-generico" action="<?php echo Utils::siteOptions("backend")?>" method="post">
<fieldset>
<p class="flottante-corto-1">
<span class="lungo-1">
<?php  echo Forms::select("x-status",$opzioni_status , $row->status,"lunga",'style=" width:140px;"')?>
</span></p>


<p class="pulsante-senza">
<input type="submit" class="pulsante-invia-corto" id="Submit" value="invia" name="Submit">
</p>
</fieldset>
<?php echo Forms::hidden("id",$row->id)?>
<?php echo Forms::hidden("m",$module)?>
<?php echo Forms::hidden("t","statusCv")?>
</form>    

		</td>
<td class="small">
		<?php echo Utils::href(Utils::makeLink($module,"openCv",array("id"=>$row->id)),'scheda',array("class"=>"button"));?>
		</td>
		<td class="small ultimo">
		<?php 
			echo Utils::href(Utils::makeLink($module,"deleteCv",array("id"=>$row->id)),"cancella",array("onclick"=>'return confirm(\'Sei sicuro di voler eliminare questo elemento?\')',"class"=>"button red"));
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