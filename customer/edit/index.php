<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');
    
    $id = vGET('id');
    
    $query = mysql_query('SELECT * FROM `customer` WHERE `id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $customer = mysql_fetch_array($query);
    
    $title = 'Kunde bearbeiten';
    
    write_header($title);
    
    linenav('Zur&uuml;ck', '../', 'Speichern', 'javascript:if($(\'#form\').valid()) { document.getElementById(\'form\').submit(); }', 'icon-chevron-left', 'icon-fire icon-white');
    
    ?>
        </div>
        <form id="form" name="form" method="POST" enctype="multipart/form-data" action="save.php">
        <div class="row-fluid">
        	
        	<div class="span12">
                <h1><?=$title?></h1>
                
                <hr>
                
                <div class="row-fluid">
                    <div class="span6">
                    
                        <h2>Kundendaten</h2>
                        <br />
                	    
                        <label for="prename">Vorname</label><input id="prename" name="customer[prename]" type="text" value="<?=$customer['prename']?>" class="required" minlength="2" />
                        <label for="name">Name</label><input id="name" name="customer[name]" type="text" value="<?=$customer['name']?>" class="required" minlength="2" />
                        <label for="company">Firma</label><input id="company" name="customer[company]" type="text" value="<?=$customer['company']?>" />
                        <label for="address">Adresse</label><input id="address" name="customer[address]" type="text" value="<?=$customer['address']?>" class="required" minlength="2" />
                        <label for="name">PLZ/Ort</label><input id="zip" name="customer[zip]" type="text" value="<?=$customer['zip']?>" style="width: 4em;"/> <input id="location" name="customer[location]" type="text" value="<?=$customer['location']?>" class="required" minlength="2" style="width: 11.1em;" />
                        <input type="hidden" name="customer[id]" id="id" value="<?=$customer['id']?>" />
                    </div>
                    
                        
                    <div class="span6">
                  
                        <h2>Kontaktdaten</h2>
                	    <br />
                	    
                	        <label for="name">Telefon</label><input id="phone" name="customer[phone]" type="text" value="<?=$customer['phone']?>" />
                	        <label for="name">Mailadresse</label><input id="mail" name="customer[mail]" type="text" value="<?=$customer['mail']?>" />
                	        <label for="name">Web</label><input id="web" name="customer[web]" type="text" value="<?=$customer['web']?>" />
                	    
                	</div>
                	
                	<script type="text/javascript">
                	    $(document).keypress(function(e) {
                	        if(e.keyCode == 13) {
                	            if($('#form').valid()) { 
                	                document.getElementById('form').submit(); 
                	            }
                	        }
                	    });
                	</script>
                	    
                </div>
                    
         	</div>
            </form>
        </div>
        <div class="row-fluid">
    	
    <?php
    
    linenav();    
    
    write_footer();
    
?>