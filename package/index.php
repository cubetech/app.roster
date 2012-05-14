<?php

	define('dire', '../');
	include(dire . '_env/exec.php');
	
	$customer_id = vGET('customer_id');
	$cquery = '';
	$newid = '';
	$adddel = '';
	if(isset($customer_id) && $customer_id!='' && $customer_id > 0) {
	    $cquery = ' AND p.customer_id="' . $customer_id . '"';
	    $newid = '?customer_id=' . $customer_id;
        $query = mysql_query('SELECT * FROM `customer` WHERE `id`="'.$customer_id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        $customer = mysql_fetch_array($query);
        $titleadd = ' von '.$customer['prename'] . ' ' . $customer['name'];
        $adddel = '&customer_id=' . $customer_id;
	}
		
	$where = 'WHERE grade="active" AND p.delete!=1';
	$show = vGET('show');
	if($show=='all') {
	    $where = '';
	    $customer_id = false;
	}
	
	$package = array();
    $query = mysql_query('SELECT p.*, 
                                 (SELECT COUNT(*) FROM packageitem WHERE package_id=p.id AND back=0) as item,
                                 s.status as statusname,
                                 s.grade as grade
                            FROM package p 
                            LEFT JOIN
                            status s ON (p.status = s.id)
                            ' . $where . '
                            ' . $cquery . '
                            ORDER BY p.id') or sqlError(__FILE__,__LINE__,__FUNCTION__);
	while($fetch=mysql_fetch_array($query))
	    array_push($package, $fetch);
		    
	$title = 'Ausleihpakete' . @$titleadd;
		
	write_header($title);
    
    if($show=='all') {
        addbutton('Nur aktive anzeigen', dire . 'package/?show=active', 'btn-inverse', 'icon-resize-small icon-white');
    } else {
        addbutton('Alle anzeigen', dire . 'package/?show=all', 'btn-inverse', 'icon-resize-full icon-white');
    }
    
    linenav('Dashboard', dire, 'Neues Paket erstellen', dire . 'package/new/' . $newid);
	
	?>
	    
	    <h1><?php print $title?></h1>
	    
	    <table class="table table-striped">
	        <thead>
	            <tr>
	                <th>#</th>
	                <th>Name</th>
	                <th>Ausgeliehen an</th>
	                <th>Artikel</th>
	                <th>Status</th>
	                <th></th>
	            </tr>
	        </thead>
	        <tbody>
	    
	<?php
	    
	foreach($package as $p) {
	
	    if($p['delete']==1) {
	        $p['statusname'] = 'Gel&ouml;scht';
	    }
	    	
	    echo '
	            <tr>
	                <td>'.$p['id'].'</td>
	                <td><a href="'.dire.'package/detail/?id='.$p['id'].'">'.$p['name'].'</a></td>
	                <td>'.$p['customer'].'</td>
	                <td>' . $p['item'] . '</td>
	                <td>' . $p['statusname'] . '</td>
	                <td>' .
	                gen_right_btn('', dire . 'package/print/?id=' . $p['id'], 'icon-print icon-white', 'btn btn-mini btn-inverse', 'Paketschein drucken', false) .
	                gen_right_btn('', 'edit/?id=' . $p['id'], 'icon-pencil icon-white', 'btn btn-mini btn-warning', 'Paket bearbeiten', false) .
	                gen_right_btn('', 'delete/?id=' . $p['id'] . $adddel, 'icon-remove icon-white', 'btn btn-mini btn-danger', 'Paket l&ouml;schen', false) .
	                gen_right_btn('', dire . 'package/return/?id=' . $p['id'], 'icon-refresh icon-white', 'btn btn-mini btn-primary', 'Paket zur&uuml;ckbuchen', false) . '</td>
	            </tr>
	        ';
	        
	}
	
	echo '
	        </tbody>
	    </table>
	    ';
	    
	write_footer();
	
?>