<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');
    
    $category = array();
    $query = mysql_query('SELECT * FROM `category`') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    while($fetch=mysql_fetch_array($query)) {
        array_push($category, $fetch);
    } //while
    
    $condition = array();
    $query = mysql_query('SELECT * FROM `condition`') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    while($fetch=mysql_fetch_array($query)) {
        array_push($condition, $fetch);
    } //while
    
    $title = 'Neuer Artikel hinzuf&uuml;gen';
    
    write_header($title);
    
    linenav('Zur&uuml;ck', '../', 'Speichern', 'javascript:document.getElementById(\'form\').submit();', 'icon-chevron-left', 'icon-fire icon-white');
    
    ?>

        	<div class="span3">
        		<div class="well sidebar-nav">
        			<ul class="nav nav-list">
        				<li class="nav-header">Kategorie</li>
        				<?
        				    foreach($category as $c) {
        				        echo '<li><input type="checkbox" name="category" value="'.$c['id'].'"> '.$c['name'].'</li>';
        				    } //foreach
        				        
        				?>
        			</ul>
        		</div>
        	</div>
        	
        	<div class="span9">
        	    <form id="form" name="form" method="POST" action="save.php">
            	    <h1><?=$title?></h1>
            	    
            	    <hr>
            	
                	<div class="row-fluid">
                	  <div class="span4">
                	  
                	    <h2>Artikel</h2>
                	    <br />
                	    
                	    <label for="name">Name</label><input id="name" name="name" type="text" value="" />
                	    <label for="description">Beschreibung</label><textarea name="description" id="description"></textarea>
                	    
                	  </div><!--/span-->
                	  <div class="span4">
                	  
                	    <h2>Anschaffung</h2>
                   	    <br />
                   	    
                   	    <label for="name">Datum</label><input id="datepicker" name="datepicker" type="text" value="" />
                   	    <label for="name">Preis</label>
                   	    <div class="input-prepend">
                   	                    <span class="add-on span3" style="margin-left: 0;">CHF</span>
                   	                    <input id="name" name="name" class="span6" type="text" value="" />
                        </div>
                   	    <label for="name">Zustand</label>
                   	    <select name="zustand">
                   	        <?php
                   	            foreach($condition as $c) {
                   	                echo '<option value="'.$c['id'].'">'.$c['name'].'</option>
                   	                ';
                   	            }
                   	        ?>
                   	    </select>
                   	    <label for="name">Ort</label><input id="name" name="name" type="text" value="" />
                   	    
                   	  </div><!--/span-->
                	  <div class="span4">
                	  
                	    <h2>Weiteres</h2>
                   	    <br />
                   	    
                   	    <label for="name">Name</label><input id="name" name="name" type="text" value="" />
                   	    <label for="name">Name</label><input id="name" name="name" type="text" value="" />
                   	    <label for="name">Name</label><input id="name" name="name" type="text" value="" />
                   	    
                	  </div><!--/span-->
                	</div>
                 </form>
         	</div>
    	
    <?php
    
    linenav();
    
    write_footer();

?>