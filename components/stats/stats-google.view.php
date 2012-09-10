<?php
/* defaults */
$month = $workspace->getGet('month',date('n') );
$year = $workspace->getGet('year',date('Y') );

/* submission? */

/* cleanse lookups */
$aProfiles = $workspace->get("profiles");

$data->setMonth($month,$year);
$visits = $data->getVisitors();
$views = $data->getPageviews();
/* build tables */

$tot_visite = 0;
$tot_view = 0;
	
foreach($visits as $day => $visit) { 
	$flot_datas_visits[]	= '[new Date('.$year.' ,'.($month-1).', '.$day.'),'.$visit.']';
	$tot_visite += $visit;
}
foreach($views as $day => $view) { 
	$flot_datas_views[]		= '[new Date('.$year.' ,'.($month-1).', '.$day.'),'.$view.']';
	$tot_view += $view;
}
$flot_data_visits = ''.implode(',',$flot_datas_visits).'';
$flot_data_views = ''.implode(',',$flot_datas_views).'';


?>

<h2>Statistiche <?php echo $month?>/<?php echo $year?></h2>
<form method="get">
    
            <select id="profileId" name="profileId" class="lunga">
		<option value="">-- profilo --</option>
            <?php
            foreach ($aProfiles as $sKey => $sValue){
                echo '<option value="' . $sKey . '" '.(($aProfileID == $sKey)?'selected="selected"':'').'>' . $sValue . '</option>';
            }
            ?>
            </select>
	<select name="month" id="month">
		<option value="">-- Select Month --</option>
		<?php
			for($x = 1; $x <= 12; $x++):
				echo '<option value="',$x,'"',($month == $x ? ' selected="selected"' : ''),'>',Utils::parseDate(mktime(0,0,0,$x,1,2010),"%B"),'</option>';
			endfor;
		?>
	</select>
	<select name="year" id="year">
		<option value="">-- Select Year --</option>
		<?php
			for($x = 2008; $x <= date('Y'); $x++):
				echo '<option value="',$x,'"',($year == $x ? ' selected="selected"' : ''),'>',$x,'</option>';
			endfor;
		?>
	</select>
	<input type="submit" name="submit" id="submit" value="carica statistiche" />

	<?php echo Forms::hidden("m",$module)?>
	<?php echo Forms::hidden("t","google")?>
            
</form>

<script type='text/javascript' src='http://www.google.com/jsapi'></script>
<?php 
	//php time - echo tables
	if($visits_table_data) { echo '<h3>Visits</h3>', $visits_table_data,'<br />'; } 
?>

<div class="spazio-20"></div>
<h3>Visite (<?php echo $tot_visite?> totali per <?php echo $month?>/<?php echo $year?>)</h3>
<div class="spazio-10"></div>
<div class="placeholder" id="visite">

</div>

<script type='text/javascript'>
google.load('visualization', '1', {'packages':['annotatedtimeline'], 'language' : 'it'});
google.setOnLoadCallback(chartVisite);
function chartVisite() {
	var data = new google.visualization.DataTable();
	data.addColumn('date', 'Date');
	data.addColumn('number', 'Visite');
	data.addRows([<?php echo $flot_data_visits; ?>]);
	
	var chart = new google.visualization.AnnotatedTimeLine(document.getElementById('visite'));
	chart.draw(data, {displayAnnotations: true});
}
</script>
<div class="spazio-20"></div>
<h3>Visualizzazioni di pagine (<?php echo $tot_view?> totali per <?php echo $month?>/<?php echo $year?>)</h3>
<div class="spazio-10"></div>
<div class="placeholder" id="views">

</div>
<script type="text/javascript">
google.load('visualization', '1', {'packages':['corechart'], 'language' : 'it'});
google.setOnLoadCallback(chartViews);
function chartViews() {
	// Some raw data (not necessarily accurate)
	var data = new google.visualization.DataTable();
	data.addColumn('date', 'Date');
	data.addColumn('number', 'Visualizzazioni');
	
	data.addRows([<?php echo $flot_data_views?>]);
	
	var chart = new google.visualization.AreaChart(document.getElementById('views'));
	chart.draw(data);
}


</script>
<div class="spazio-20"></div>
<h3>Referrer</h3>
<div class="spazio-10"></div>
<div class="placeholder" id="referrer">

</div>

<script type="text/javascript">
google.load("visualization", "1", {packages:["corechart"], 'language' : 'it'});
google.setOnLoadCallback(chartRef);
function chartRef() {
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Sito');
	data.addColumn('number', 'totale');
	<?php 
	foreach($data->getReferrers() as $key => $value):
		$flot_data_reff [] = "['".$key."' ,". $value."]";
	endforeach;?>
	data.addRows([<?php echo implode(",",$flot_data_reff)?>]);
	//data.addRows(2);
	
	var chart = new google.visualization.ColumnChart(document.getElementById('referrer'));
	chart.draw(data);
}
</script>
<div class="spazio-20"></div>
<h3>Browser</h3>
<div class="spazio-10"></div>
<div class="placeholder" id="browser">

</div>

<script type="text/javascript">
google.load("visualization", "1", {packages:["corechart"], 'language' : 'it'});
google.setOnLoadCallback(chartBrowser);
function chartBrowser() {
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Browser');
	data.addColumn('number', 'totale');
	<?php 
	foreach($data->getBrowsers() as $key => $value):
		$flot_data_br [] = "['".$key."' ,". $value."]";
	endforeach;?>
	data.addRows([<?php echo implode(",",$flot_data_br)?>]);
	//data.addRows(2);
	
	var chart = new google.visualization.BarChart(document.getElementById('browser'));
	chart.draw(data);
}
</script>



