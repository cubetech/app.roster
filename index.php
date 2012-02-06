<?php

    define('dire', '');
    include(dire . '_env/exec.php');
    
    write_header('Dashboard');
    
    ?>
    
    	<div class="span3">
    		<div class="well sidebar-nav">
    			<ul class="nav nav-list">
    				<li class="nav-header">Statistik</li>
    				<li><a href="#">3 Artikel</a></li>
    				<li><a href="#">37 Kunden</a></li>
    				<li><a href="#">832 Ausleihpakete</a></li>
    			</ul>
    		</div>
    	</div>
    	
    	<div class="span9">
    	</div>
    	
    <?php
    
    write_footer();
    
?>
