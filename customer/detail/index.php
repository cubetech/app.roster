<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');
    
    $id = vGET('id');
    
    $query = mysql_query('SELECT * FROM `customer` WHERE `id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $customer = mysql_fetch_array($query);
    
    $title = 'Kunde';
    
    write_header($title);
    
    addbutton('Bearbeiten', '../edit/?id=' . $id, 'btn-warning', 'icon-pencil icon-white');
    if($customer['delete']==0) {
        addbutton('L&ouml;schen', '../delete/?id=' . $id);
    } else {
        addbutton('Wiederherstellen', '../delete/?id=' . $id, '', 'icon-ok');
    }
    linenav('Zur&uuml;ck', '../', 'Paket erstellen', dire . 'package/new/?customer_id=' . $id, 'icon-chevron-left', 'icon-fire icon-white');
    
    ?>
        </div>
        <div class="row-fluid">
        	
        	<div class="span12">
                <h1><?=$title?></h1>
                
                <hr>
                
                <div class="row-fluid">
                    <div class="span6">
                    
                        <h2>Kundendaten</h2>
                        <br />
                        
                        <dl>
                            <dt>Vorname</dt><dd><?=$customer['prename']?></dd>
                            <dt>Name</dt><dd><?=$customer['name']?></dd>
                            <dt>Firma</dt><dd><?=$customer['company']?></dd>
                            <dt>Adresse</dt><dd><?=$customer['address']?></dd>
                            <dt>PLZ/Ort</dt><dd><?=$customer['zip']?> <?=$customer['location']?></dd>
                        </dl>
                    
                    </div>
                    
                        
                    <div class="span6">
                  
                        <h2>Kontaktdaten</h2>
                	    <br />
                	    
                        <dl>
                            <dt>Telefon</dt><dd><?=$customer['phone']?></dd>
                            <dt>Mailadresse</dt><dd><?=$customer['mail']?></dd>
                            <dt>Web</dt><dd><?=$customer['web']?></dd>
                        </dl>
                	                    	    
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