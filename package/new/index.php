<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');
    
    $item_id = vGET('item_id');
    
    if(isset($item_id) && $item_id!='') {
        $query = mysql_query('SELECT * FROM `item` WHERE `id`="'.$item_id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        $item = mysql_fetch_array($query);
        if(!$item) {
            error('own', 'Dieser Artikel wurde nicht gefunden.');
        } elseif($item['delete']!=0) {
            error('own', 'Dieser Artikel ist gel&ouml;scht. Bitte zuerst wiederherstellen.');
        }
    }
    
    $customer = array();
    $compcust = array();
    $query = mysql_query('SELECT * FROM `customer`') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    while($fetch=mysql_fetch_array($query)) {
        if(!$fetch['company'] || $fetch['company']=='') {
            array_push($customer, $fetch);
        } else {
            array_push($compcust, $fetch);
        }
    }
    
    $title = 'Paket erstellen';
    
    write_header($title);
    
    linenav('Zur&uuml;ck', '../', 'Speichern', 'javascript:if($(\'#form\').valid()) { document.getElementById(\'form\').submit(); }', 'icon-chevron-left', 'icon-fire icon-white');
    
    ?>
        </div>
        <form id="form" name="form" method="POST" enctype="multipart/form-data" action="save.php">
        <div class="row-fluid">
        	<div class="span3">
        		<div class="well sidebar-nav">
        			<ul class="nav nav-list">
        				<li class="nav-header">Kategorie</li>
        			</ul>
        		</div>
        	</div>
        	
        	<div class="span9">
                <h1><?=$title?></h1>
                
                <hr>
                
                <div class="row-fluid">
                  <div class="span6">
                  
                    <h2>Artikel</h2>
                    <br />
                    
                    <dl>
                        <dt>ID</dt><dd><?=$item['id']?></dd>
                        <dt>Name</dt><dd><?=$item['name']?></dd>
                        <dt>Barcode</dt><dd><img src="<?=dire?>barcode/?id=<?=$item['barcode']?>" /></dd>
                    </dl>
                    
                  </div><!--/span-->
                  <div class="span6">
                  
                    <h2>Kunde</h2>
                	    <br />
                	    <select id="customer" name="customer">
                	        <option id="manual" value="manual">Manuell eingeben</option>
                	        <option>--------------------</option>
                                <?
                                    foreach($compcust as $c) {
                                        echo '<option value="'.$c['id'].'">'.$c['company'].' - '.$c['prename'].' '.$c['name'].'</option>';
                                    }

                                    foreach($customer as $c) {
                                        echo '<option value="'.$c['id'].'">'.$c['prename'].' '.$c['name'].'</option>';
                                    }
                                ?>
                	    </select>
                	    
                	    <div id="manual_form">
                	    
                	        <label for="name">Vorname</label><input id="prename" name="data[prename]" type="text" value="" class="required" minlength="2" />
                	        <label for="name">Name</label><input id="name" name="data[name]" type="text" value="" class="required" minlength="2" />
                	        <label for="name">Firma</label><input id="company" name="data[company]" type="text" value="" />
                	        <label for="name">Adresse</label><input id="address" name="data[address]" type="text" value="" class="required" minlength="2" />
                	        <label for="name">PLZ/Ort</label><input id="zip" name="data[zip]" type="text" value="" style="width: 4em;"/> <input id="location" name="data[location]" type="text" value="" class="required" minlength="2" style="width: 11.1em;" />
                	        <label for="name">Telefon</label><input id="phone" name="data[phone]" type="text" value="" />
                	        <label for="name">Mailadresse</label><input id="mail" name="data[mail]" type="text" value="" />
                	        <label for="name">Web</label><input id="web" name="data[web]" type="text" value="" />
                	        <input type="hidden" id="hidden" name="data[hidden]" value="false" />
                	    
                	    </div>
                	    
                        <script type="text/javascript">
                            $('#customer').change(function() {
                                if($(this).find('option:selected').attr('id')!='manual') {
                                    $('#manual_form').hide();
                                    $('#hidden').val('true');
                                } else {
                                    $('#manual_form').show();
                                    $('#hidden').val('false');
                                }
                            });
                        </script>
                        
                  </div><!--/span-->
                </div>
         	</div>
            </form>
        </div>
        <div class="row-fluid">
    	
    <?php
    
    linenav();    
    
    write_footer();
    
?>