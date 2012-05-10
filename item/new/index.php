<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');
        
    $code = vGET('bid');
    if(isset($code)) {
        $query = mysql_query('SELECT * FROM `item` WHERE `barcode`="'.$code.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        $item = mysql_fetch_array($query);
        if($item) {
            error('own', 'Mit diesem Barcode kann kein neuer Artikel erstellt werden, da bereits ein Artikel assoziiert ist.<br />
                          Sie m&uuml;ssen erst f&uuml;r diesen Artikel einen neuen Barcode generieren, danach kann dieser Barcode verwendet werden.<br /><br />
                          ' . gen_left_btn('Artikel anzeigen', dire . 'item/detail/?id=' . $item['id'], 'icon-forward', 'btn') . '<br />');
        }
    }
    
    $scode = vGET('scode', true, 'string');
    if(isset($scode) && $scode!='') {
        $code = code13($scode);
    }
    
    $category = array();
    $query = mysql_query('SELECT * FROM `category`') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    while($fetch=mysql_fetch_array($query)) {
        array_push($category, $fetch);
    } //while

    $title = 'Neuer Artikel hinzuf&uuml;gen';
    
    if(!isset($code) || $code=='') {
        $code = code13();
    }
    $query = mysql_query('SELECT * FROM `barcode` WHERE `id`="'.$code.'"');
    $barcode = mysql_fetch_array($query);
        
    $customfields = array();
    $query = mysql_query('SELECT * FROM `customfield` WHERE `type`="item"');
    while($fetch=mysql_fetch_array($query))
        array_push($customfields, $fetch);
        
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
        				<?
        				    foreach($category as $c) {
        				        echo '<li><input type="checkbox" name="category[]" value="'.$c['id'].'"> '.$c['name'].'</li>';
        				    } //foreach
        				        
        				?>
        			</ul>
        			<br />
        			<ul class="nav nav-list">
                        <li class="nav-header">Bild einf&uuml;gen</li>
                        <li><input type="file" name="photoimg" id="photoimg" /></li>
                        <li><div id='preview' /></li>
        			</ul>
        		</div>
        	</div>
        	
        	<div class="span9">
                <h1><?=$title?></h1>
                
                <hr>
                
                <div class="row-fluid">
                  <div class="span4">
                  
                    <h2>Artikel</h2>
                    <br />
                    
                    <label for="name">Name</label><input id="name" name="data[name]" type="text" value="" class="required" minlength="2" />
                    <label for="comments">Beschreibung</label><textarea name="data[comments]" id="comments"></textarea>
                    <label for="barcode">Barcode</label><img src="<?=dire?>barcode/?code=<?=$barcode['barcode']?>" id="barcode" alt="barcode" />
                    <input type="hidden" name="barcode" value="<?=$code?>" />
                    
                  </div><!--/span-->
                  <div class="span4">
                  
                    <h2>Anschaffung</h2>
                	    <br />
                	    
                	    <label for="datepicker">Datum</label><input id="datepicker" name="data[datepicker]" type="text" value="<?=date('d.m.Y')?>" />
                	    <label for="price">Preis</label>
                	    <div class="input-prepend">
                	                    <span class="add-on span3" style="margin-left: 0;">CHF</span>
                	                    <input id="price" name="data[buyprice]" class="span6" type="text" value="" />
                    </div>
                    <label for="buycondition">Zustand</label>
                    <input id="buycondition" name="data[buycondition]" type="text" />
                    <label for="buyplace">Kaufort</label>
                    <input id="buyplace" name="data[buyplace]" type="text" />
                	    
                	  </div><!--/span-->
                  <div class="span4">
                  
                    <h2>Weiteres</h2>
                	    <br />
                	    
                	    <?php
                	        
                	        foreach($customfields as $f) {
                	            if($f['fieldtype']=='text') {
                	                echo '<label for="'.$f['name'].'">'.$f['fullname'].'</label><input type="'.$f['fieldtype'].'" name="custom['.$f['name'].']" id="'.$f['name'].'" />';
                	            }
                	        }
                	        
                	    ?>
                	    
                	    <!--<label for="name">Name</label><input id="name" name="name" type="text" value="" />
                	    <label for="name">Name</label><input id="name" name="name" type="text" value="" />
                	    <label for="name">Name</label><input id="name" name="name" type="text" value="" />
                	    -->
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