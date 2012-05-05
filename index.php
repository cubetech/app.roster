<?php

    define('dire', '');
    include(dire . '_env/exec.php');
    
    $query = mysql_query('SELECT COUNT(*) FROM `item` WHERE `delete`=0') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $itema = mysql_fetch_row($query);
    
    $query = mysql_query('SELECT COUNT(*) FROM `package` WHERE `delete`=0 AND `status`=6') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $package = mysql_fetch_row($query);
    
    write_header('Dashboard');
    
    ?>
    
    	<div class="span3">
    		<div class="well sidebar-nav">
    			<ul class="nav nav-list">
    				<li class="nav-header">Statistik</li>
    				<li><a href="item/"><?=$itema[0]?> Artikel</a></li>
    				<li><a href="package/"><?=$package[0]?> Ausleihpakete</a></li>
    			</ul>
    		</div>
    	</div>
    	
    	<div class="span9">
    	</div>
    	
    <?php
    
    write_footer();
    
?>
