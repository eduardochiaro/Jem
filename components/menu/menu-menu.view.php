<?php
foreach($data as $key => $row){
	Utils::setThisMenu($key, $row);	
}
?>
<ul>
<?php foreach($data as $key => $row):?>
<?php
$url = Utils::makeLink($row["option"],$row["task"],$row["append"]);
if($row["url"]){
	$url = $row["url"];
}
?>
<li><a href="<?php echo $url?>" title="<?php echo $row["title"]?>"<?php if(Utils::isThisMenu($workspace, $key, $row)):?> class="selected"<?php endif;?>><span></span><?php echo $row["title"]?></a>
	<?php if($row->menu->voice):?>
    <ul<?php if(Utils::isThisMenu($key)):?> style="display:block;"<?php endif;?>>
        <?php foreach($row->menu->voice as $sub):?>
		<?php
        $url = Utils::makeLink($sub["option"],$sub["task"],$sub["append"]);
        if($sub["url"]){
            $url = $sub["url"];
        }
        ?>
        <li><a href="<?php echo $url?>"><?php echo $sub["title"]?></a></li>
        <?php endforeach;?>
    </ul>
    <?php endif;?>
</li>
<?php endforeach;?>
</ul>