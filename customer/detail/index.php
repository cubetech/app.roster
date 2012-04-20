<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');
    
    $id = vGET('id');
    
    $query = mysql_query('SELECT * FROM `customer` WHERE `id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $customer = mysql_fetch_array($query);
    
    $title = 'Kunde';
    
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
                	    
                        <label for="name">Vorname</label><input id="prename" name="client[prename]" type="text" value="" class="required" minlength="2" />
                        <label for="name">Name</label><input id="name" name="client[name]" type="text" value="" class="required" minlength="2" />
                        <label for="name">Firma</label><input id="company" name="client[company]" type="text" value="" />
                        <label for="name">Adresse</label><input id="address" name="client[address]" type="text" value="" class="required" minlength="2" />
                        <label for="name">PLZ/Ort</label><input id="zip" name="client[zip]" type="text" value="" style="width: 4em;"/> <input id="location" name="client[location]" type="text" value="" class="required" minlength="2" style="width: 11.1em;" />
                    </div>
                    
                        
                    <div class="span6">
                  
                        <h2>Kontaktdaten</h2>
                	    <br />
                	    
                	        <label for="name">Telefon</label><input id="phone" name="client[phone]" type="text" value="" />
                	        <label for="name">Mailadresse</label><input id="mail" name="client[mail]" type="text" value="" />
                	        <label for="name">Web</label><input id="web" name="client[web]" type="text" value="" />
                	    
                	</div>
                	    
                </div>
                    
         	</div>
            </form>
        </div>
        <div class="row-fluid">
    	
    <?php
    
    linenav();    
    
    write_footer();
    
?>