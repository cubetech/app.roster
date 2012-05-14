<?php
	
	define('dire', '../../');
	include(dire . '_env/exec.php');
	
	$id = iGET('id');
	$title = 'Artikeldetails Nr. ' . $id;
	
    $query = mysql_query('SELECT i.*, 
                                 b.barcode as fullbarcode,
                                 m.name as imgname,
                                 s.status as statusname
                            FROM `item` i
                            LEFT JOIN
                            `barcode` b ON i.barcode = b.id
                            LEFT JOIN
                            `status` s ON i.status = s.id
                            LEFT JOIN
                            `image` m on i.image = m.id
                            WHERE i.id="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $item = mysql_fetch_array($query);
    
    if(!$item) {
        error('transfer');
    }
    
    $customcontent = array();
    $query = mysql_query('SELECT c.*,
                                 f.fullname as fullname
                             FROM `customcontent` c
                             LEFT JOIN
                             `customfield` f ON c.field_id = f.id
                             WHERE c.value_id="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    while($fetch=mysql_fetch_array($query))
        array_push($customcontent, $fetch);
        
    $category = array();
    $query = mysql_query('SELECT c.*,
                                 o.name as name 
                            FROM `categoryitem` c
                            LEFT JOIN
                            `category` o ON c.category_id = o.id
                            WHERE `item_id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    while($fetch=mysql_fetch_array($query))
        array_push($category, $fetch);
        
    $history = array();
    $query = mysql_query('SELECT pi.*,
                                p.name as name,
                                p.customer as customer,
                                p.person as person,
                                p.id as pid,
                                p.startdate as startdate,
                                p.duedate as duedate
                                FROM `packageitem` pi
                                LEFT JOIN
                                `package` p ON pi.package_id = p.id
                                WHERE pi.item_id="'.$id.'"
                                AND pi.back="1"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    while($fetch=mysql_fetch_array($query))
        array_push($history, $fetch);
        
    if($item['status']==5) {
        $query = mysql_query('SELECT * FROM `packageitem` WHERE `item_id`="'.$id.'" AND `back`="0"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        $packageitem = mysql_fetch_array($query);
    }

	write_header($title);
	
    addbutton('Barcode drucken', dire . 'barcode/print/?id=' . $item['barcode'], 'btn-inverse', 'icon-print icon-white');
    addbutton('Bearbeiten', '../edit/?id=' . $id, 'btn-warning', 'icon-pencil icon-white');
    if($item['delete']==0) {
        addbutton('L&ouml;schen', '../delete/?id=' . $id);
    } else {
        addbutton('Wiederherstellen', '../delete/?id=' . $id, '', 'icon-ok');
    }
    linenav('Zur&uuml;ck', '../', 'Paket erstellen', dire . 'package/new/?item_id=' . $id, 'icon-chevron-left', 'icon-fire icon-white');
	
	?>
	
	<div class="span3">
		<div class="well sidebar-nav">
            <ul class="nav nav-list">
            	<li class="nav-header">Kategorie</li>
            	<?php
            	    foreach($category as $c) {
            	        echo '<li><a href="'.dire.'category/?id='.$c['category_id'].'">'.$c['name'].'</a></li>';
            	    }
            	?>
            </ul>
            <ul class="nav nav-list">
            	<li class="nav-header">Bild</li>
                <li>
                <?php
                    if(isset($item['imgname']) && $item['imgname']!='') {
                        echo '<img class="preview" src="'.dire.'_image/item/'.$item['imgname'].'" alt="'.$item['imgname'].'" />';
                    } else {
                        echo '<i>Kein Bild vorhanden</i>';
                    }
                ?>
                </li>
            </ul>
		</div>
	</div>
	
	<div class="span9">
	    <h1><?php print $title?></h1>
	    
	    <hr>
	
    	<div class="row-fluid">
    	  <div class="span4">
    	  
    	    <h2>Artikel</h2>
    	    
    	    <dl>
    	        <dt>Name</dt><dd><?php print $item['name']?></dd>
    	        <dt>Beschreibung</dt><dd><?php print $item['comments']?></dd>
    	        <dt>Status</dt><dd><?php print $item['statusname']?>
    	        <?php
    	        
    	            if($item['status']==5) {
	                    echo '<strong> - <a href="'.dire.'package/detail/?id='.$packageitem['package_id'].'">Paket ID '.$packageitem['package_id'].'</a></strong>';
    	            }
    	            
    	        ?></dd>
    	        <dt>Barcode</dt><dd><a href="<?php print dire?>barcode/detail/?id=<?php print $item['barcode']?>"><img src="<?php print dire?>barcode/?code=<?php print $item['fullbarcode']?>" alt="barcode" /></a></dd>
    	    </dl>
    	        	    
    	  </div><!--/span-->
    	  <div class="span4">
    	  
    	    <h2>Anschaffung</h2>

            <dl>
                <dt>Datum</dt><dd><?php print date('d.m.Y', $item['buydate'])?></dd>
                <dt>Preis</dt><dd>CHF <?php print $item['buyprice']?></dd>
                <dt>Zustand</dt><dd><?php print $item['buycondition']?></dd>
                <dt>Kaufort</dt><dd><?php print $item['buyplace']?></dd>
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
    	
    	<?php
    		if(count($history) > 0){
    	?>
    	
    	<div class="row-fluid">
    	  <div class="span12" style="padding-top: 2em;">
    	      <h2>Ausleih-History (nur vergangene)</h2>
    	      
    	      <table class="table table-striped" id="items">
    	          <thead>
    	              <tr>
    	                  <th>#</th>
    	                  <th>Name</th>
    	                  <th>Ausleiher</th>
    	                  <th>Ansprechperson</th>
    	                  <th>Ausleihdauer</th>
    	              </tr>
    	          </thead>
    	          <tbody id="itembody">
    	      
    	      
                <?php
                
                    foreach($history as $h) {
    	          
                        echo '
                                <tr id="'.$h['pid'].'">
                                    <td>'.$h['pid'].'</td>
                                    <td>'.$h['name'].'</td>
                                    <td>'.$h['customer'].'</td>
                                    <td>'.$h['person'].'</td>
                                    <td>'.date('d.m.Y', $h['startdate']).' - '.date('d.m.Y', $h['duedate']).'</td>
                                </tr>
                            ';
    	          
    	          }
    	          
    	      ?>
    	      
    	      </tbody>
    	      </table>
                
    	  </div>
    	</div>
    	
    	<?php
    		}//if
    	?>
    	
	</div>
	
	<?php
	
	write_footer();
	
?>