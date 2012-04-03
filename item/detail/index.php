<?php
	
	define('dire', '../../');
	include(dire . '_env/exec.php');
	
	$id = vGET('id');
	$title = 'Artikeldetails Nr. ' . $id;
	
    $query = mysql_query('SELECT i.*, 
                                 c.name AS categoryname,
                                 b.barcode as fullbarcode,
                                 z.name as condname,
                                 p.name as placename,
                                 m.name as imgname
                            FROM `item` i
                            LEFT JOIN 
                            `category` c ON i.category = c.id
                            LEFT JOIN
                            `barcode` b ON i.barcode = b.id
                            LEFT JOIN
                            `buyplace` p ON (i.buyplace = p.id)
                            LEFT JOIN
                            `condition` z ON i.buycondition = z.id
                            LEFT JOIN
                            `image` m on i.image = m.id
                            WHERE i.id="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $item = mysql_fetch_array($query);
    
    $customcontent = array();
    $query = mysql_query('SELECT c.*,
                                 f.fullname as fullname
                             FROM `customcontent` c
                             LEFT JOIN
                             `customfield` f ON c.field_id = f.id
                             WHERE c.value_id="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
                             
    while($fetch=mysql_fetch_array($query))
        array_push($customcontent, $fetch);

	write_header($title);
	
	linenav('Zur&uuml;ck', '../');
	
	?>
	
	<div class="span3">
		<div class="well sidebar-nav">
            <ul class="nav nav-list">
            	<li class="nav-header">Kategorie</li>
                <li><?=$item['categoryname']?></li>
            </ul>
            <ul class="nav nav-list">
            	<li class="nav-header">Bild</li>
                <li><img src="<?=dire?>_image/item/<?=$item['imgname']?>" alt="<?=$item['imgname']?>" /></li>
            </ul>
		</div>
	</div>
	
	<div class="span9">
	    <h1><?=$title?></h1>
	    
	    <hr>
	
    	<div class="row-fluid">
    	  <div class="span4">
    	  
    	    <h2>Artikel</h2>
    	    
    	    <dl>
    	        <dt>Name</dt><dd><?=$item['name']?></dd>
    	        <dt>Beschreibung</dt><dd><?=$item['comments']?></dd>
    	        <dt>Barcode</dt><dd><img src="<?=dire?>barcode/?code=<?=$item['fullbarcode']?>" alt="barcode" /></dd>
    	    </dl>
    	        	    
    	  </div><!--/span-->
    	  <div class="span4">
    	  
    	    <h2>Anschaffung</h2>

            <dl>
                <dt>Datum</dt><dd><?=date('d.m.Y', $item['buydate'])?></dd>
                <dt>Preis</dt><dd>CHF <?=$item['buyprice']?></dd>
                <dt>Zustand</dt><dd><?=$item['condname']?></dd>
                <dt>Kaufort</dt><dd><?=$item['placename']?></dd>
            </dl>
       	    
       	  </div><!--/span-->
    	  <div class="span4">
    	  
    	    <h2>Weiteres</h2>

            <dl>
                   	    
       	    <?php
       	        
       	        foreach($customcontent as $c) {
       	        
       	            echo '<dt>' . $c['fullname'] . '</dt><dd>' . $c['value'] . '</dd>';
                    
       	        }
       	        
       	    ?>
       	    
       	    </dl>
       	    
       	    <!--<label for="name">Name</label><input id="name" name="name" type="text" value="" />
       	    <label for="name">Name</label><input id="name" name="name" type="text" value="" />
       	    <label for="name">Name</label><input id="name" name="name" type="text" value="" />
       	    -->
    	  </div><!--/span-->
    	</div>
	</div>
		
	<?php
	
	write_footer();
	
?>