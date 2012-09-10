<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php Utils::writeHeader($workspace)?>
</head>

<body class="login">
	<div id="login">
    	<h1>JEM</h1>
			<?php Utils::getViewError($workspace,$module,$task,$data); ?>
            <?php Utils::getViewInfo($workspace,$module,$task,$data); ?>

            <form id="loginform" action="<?php echo Utils::makeLink("access","access")?>" method="post" class="form-login">
                <p>
                    <label id="" for="" class="">nome utente</label>
                    <input type="text" name="username" id="username" class="input" value="" size="20" />
                </p>
                <p>
                    <label id="" for="" class="">password</label>
                    <input type="password" name="password" id="password" class="input" value="" size="20" />
            
                </p>
                
                <p class="submit">
                    <input type="submit" name="submit" id="submit" value="log in" class="submit" />
                </p>
            </form>

            
               </form>
            
            <div class="clear spazio-20"></div>
            </div><!--fine-form-->

	</div>
    <div id="copyright">&copy; <?php echo date("Y")?> all rights reserved</div>
</body>
</html>