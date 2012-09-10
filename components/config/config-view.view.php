<div id="bordo"> <!--inizio #bordo-->
     
     	<h2 class="titolo">modifica parametri</h2>
        
        <div id="maschera-form"> <!--inizio #maschera-form-->
        
        	<form action="<?php echo Utils::makeLink($module,"save")?>" method="post" class="form-generico">
                                    
                	<h2> Globali</h2>    
                <fieldset>
                
                    
                    <p class="flottante-lungo">
                    	<?php echo Forms::element("Nome Sito","sitename",Forms::text("sitename",$data->sitename,"lungo-2"),"generica");?>
                    </p>
                    
                    <p class="flottante-lungo">
                    	<?php echo Forms::element("Formato Data","timeformat",Forms::text("timeformat",$data->timeformat,"lungo-2"),"generica");?>
                    </p>
                 
                </fieldset>
                	<h2> Informazioni</h2>
                <fieldset>
                
                
                    <p class="lungo-1">
                    	<?php echo Forms::element("Nome Societ&agrave;","company",Forms::text("company",$data->company,"lungo-1"),"generica");?>
                    </p>
                    <p class="flottante-lungo">
                    	<?php echo Forms::element("Indirizzo","address",Forms::text("address",$data->address,"lungo-2"),"generica");?>
                    </p>
                    <p class="flottante-lungo">
                    	<?php echo Forms::element("Citt&agrave; (provincia)","city",Forms::text("city",$data->city,"lungo-2"),"generica");?>
                    </p>
                    <p class="flottante-lungo">
                    	<?php echo Forms::element("CAP","zip",Forms::text("zip",$data->zip,"lungo-2"),"generica");?>
                    </p>
                    <p class="flottante-lungo">
                    	<?php echo Forms::element("Nazione","state",Forms::text("state",$data->state,"lungo-2"),"generica");?>
                    </p>
                    <p class="lungo-1">
                    	<?php echo Forms::element("partita iva","timeformat",Forms::text("vat",$data->vat,"lungo-1"),"generica");?>
                    </p>
                    <p class="flottante-lungo">
                    	<?php echo Forms::element("telefono","phone",Forms::text("phone",$data->phone,"lungo-2"),"generica");?>
                    </p>
                
                    <p class="flottante-lungo">
                    	<?php echo Forms::element("telefono alternativo","phoneother",Forms::text("phoneother",$data->phoneother,"lungo-2"),"generica");?>
                    </p>
                <div class="clear"></div>
                    <p class="flottante-lungo">
                    	<?php echo Forms::element("fax","fax",Forms::text("fax",$data->fax,"lungo-2"),"generica");?>
                    </p>
                
                    <p class="flottante-lungo">
                    	<?php echo Forms::element("fax alternativo","faxother",Forms::text("faxother",$data->faxother,"lungo-2"),"generica");?>
                    </p>
                <div class="clear"></div>
                    <p class="flottante-lungo">
                    	<?php echo Forms::element("cellulare","mobile",Forms::text("mobile",$data->mobile,"lungo-2"),"generica");?>
                    </p>
                
                
                </fieldset>
                	<h2> Analytics</h2>
                <fieldset>



                <div class="clear"></div>
                    <p class="flottante-lungo">
                    	<?php echo Forms::element("analytics username","analytics_username",Forms::text("analytics_username",$data->analytics_username,"lungo-2"),"generica");?>
                    </p>
                
                    <p class="flottante-lungo">
                    	<?php echo Forms::element("analytics password","analytics_password",Forms::text("analytics_password",$data->analytics_password,"lungo-2"),"generica");?>
                    </p>
                
                </fieldset>
                	<h2> Posta</h2>
                <fieldset>

                    <p class="flottante-lungo">
                    	<?php echo Forms::element("indirizzo reply","replyto",Forms::text("replyto",$data->replyto,"lungo-2"),"generica");?>
                    </p>


               
              </fieldset>
                    
                    <span class="clear spazio-10"></span>
                    
                    <p class="pulsante">
                        <input type="submit" name="Submit" value="invia modifica" id="Submit" class="pulsante-invia" />
                  	</p>
                    
              </fieldset>
            
          </form>
            
        	</div><!--fine #maschera-form-->
            
            
        
        <span class="clear"></span>
        
        <span class="spazio-10"></span>
        
        
     </div>