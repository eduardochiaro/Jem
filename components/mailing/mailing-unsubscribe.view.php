<?php
$translations = $workspace->getTranslation();
$langs = $workspace->get("languages");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php Utils::writeHeader($workspace)?>
</head>

<body class="login">
	<div id="unsubscribe">
    <ul>
    <?php foreach($langs as $language):?>
    <?php
		$sostituto		= explode(",","lang");
		$query			= $workspace->getServer('QUERY_STRING');
		$querystring	= Utils::clearFromQueryString($query, $sostituto);
		$querystring	.= ($querystring)?"&":"";
	?>
    <li><a href="<?php echo Utils::siteOptions("backend"). "?".$querystring."lang=".$language["code"]?>"><?php echo $language["language"]?></a></li>
    <?php endforeach?>
    </ul>
    <div class="clear"></div>
	<?php echo $data?>

	</div>
</body>
</html>