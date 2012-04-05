<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');
    
    $id = iGET('id');
    
    $query = mysql_query('SELECT * FROM `barcode` WHERE `id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $barcode = mysql_fetch_array($query);
    
    $query = mysql_query('SELECT * FROM `item` WHERE `barcode`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $item = mysql_fetch_array($query);
    
    $title = 'Details Barcode '.$barcode['barcode'];
    
    write_header($title);
    
    addbutton('Neuer Artikel mit diesem Barcode erstellen', dire . 'item/new/?bid=' . $id, 'btn-warning', 'icon-plus icon-white');
    linenav('Zur&uuml;ck', 'javascript:history.back();', 'Barcode neu generieren', dire . 'barcode/new/?item_id=' . $item['id'], 'icon-chevron-left', 'icon-fire icon-white');

    ?>
    
	<div class="span3">
		<div class="well sidebar-nav">
            <ul class="nav nav-list">
            	<li class="nav-header">Barcode</li>
            	<li><img class="preview" src="<?=dire?>barcode/?code=<?=$barcode['barcode']?>" alt="barcode" /></li>
            </ul>
		</div>
	</div>
	
	<div class="span9">
	    <h1><?=$title?></h1>
	    
	    <hr>
	
    	<div class="row-fluid">
    	  <div class="span5">
    	  
    	    <h2>Infos</h2>
    	    
    	    <dl>
    	        <dt>ID</dt><dd><?=$barcode['id']?></dd>
    	        <dt>Generiert am</dt><dd><?=date('d.m.Y H:i', $barcode['time'])?> Uhr</dd>
    	    </dl>
    	        	    
    	  </div><!--/span-->
    	  <div class="span6">
    	  
    	    <h2>Assoziierter Artikel</h2>

            <dl>
                <?php
                    if(!$item) {
                        echo '<dt>Kein Artikel assoziiert!</dt><dd>&nbsp;</dd>';
                    } else {
                        echo '<dt>Name</dt><dd><a href="' . dire. 'item/detail/?id=' . $item['id'] . '">' . $item['name'] . '</a></dd>';
                    }
                ?>
            </dl>
       	    
       	  </div><!--/span-->
    	</div>
	</div>
    
    <?php

    write_footer();
    
?>