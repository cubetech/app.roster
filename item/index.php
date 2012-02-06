<?php

    define('dire', '../');
    include(dire . '_env/exec.php');
    
    $query = mysql_query('SELECT i.*, 
                                 c.name AS categoryname,
                                 b.barcode as fullbarcode
                            FROM item i 
                            LEFT JOIN 
                            category c ON (i.category = c.id)
                            LEFT JOIN
                            barcode b ON (i.barcode = b.id)') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $items = array();
    while($fetch=mysql_fetch_array($query))
        array_push($items, $fetch);
        
    write_header('Artikelliste');
    
    ?>
    
		<p class="pull-left">
			<a class="btn" href="<?=dire?>">
			    <i class="icon-chevron-left"></i> Dashboard
			</a>
		</p>
        <p class="pull-right">
            <a class="btn btn-primary" href="<?=dire?>item/new/">
                <i class="icon-plus icon-white"></i> Neuer Artikel hinzuf&uuml;gen
            </a>
        </p>
        
        <br /><br /><br />
        
        <h1>Artikelliste</h1>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Barcode</th>
                    <th>Kategorie</th>
                    <th class="big5">Artikel</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
        
    <?php
        
    foreach($items as $i) {
    
        echo '
                <tr>
                    <td>'.$i['id'].'</td>
                    <td><a href="'.dire.'barcode/detail/?id='.$i['barcode'].'">'.$i['fullbarcode'].'</a></td>
                    <td><a href="'.dire.'category/?id='.$i['category'].'">'.$i['categoryname'].'</a></td>
                    <td><a href="'.dire.'item/detail/?id='.$i['id'].'">'.$i['name'].'</a></td>
                    <td>here</td>
                </tr>
            ';
            
    }
    
    echo '
            </tbody>
        </table>
        ';
        
    write_footer();
    
?>