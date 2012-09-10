<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php Utils::writeHeader($workspace)?>
</head>
	<div id="header">
    
    	<h1><a href="" title="">JEM</a></h1>
        
        <div id="menu-top"> <!--inizio #menu-servizio-->
        
            <ul>
           
                <li>salve, <em><?php echo $my->username ?></em> <span>|</span></li>
                <li><?php echo Utils::href(Utils::makeLink("access","logout"),"logout")?></li>
            
            </ul>
        
        </div> <!--fine #menu-servizio-->
        
        <div id="ui-tabs"> <!--inizio #ui-tabs-->
        <?php
		$menu = new menuController($workspace,"menu","menu");
		$menu ->execMenu();
		?>

        </div> <!--fine #ui-tabs-->
        
     </div><!--fine #header-->
     
     <div id="contenitore-generale"> <!--inizio #contenitore-generale-->
