<?php

    define('dire', '../');
    include(dire . '_env/exec.php');
    
    $del = vGET('del');
    
    $show = vGET('show');
    $where = 'WHERE i.delete!=1';
    if($show=='all') {
        $where = false;
    }

    $query = mysql_query('SELECT i.*, 
                                 c.name AS categoryname,
                                 b.barcode as fullbarcode,
                                 s.status as statusname
                            FROM item i 
                            LEFT JOIN 
                            category c ON (i.category = c.id)
                            LEFT JOIN
                            barcode b ON (i.barcode = b.id)
                            LEFT JOIN
                            status s ON (i.status = s.id)
                            ' . $where) or sqlError(__FILE__,__LINE__,__FUNCTION__);
                            
    $items = array();
    while($fetch=mysql_fetch_array($query))
        array_push($items, $fetch);
        
    write_header('Artikelliste');
    
    if($show=='all') {
        addbutton('Nur aktive anzeigen', dire . 'item/?show=active', 'btn-inverse', 'icon-resize-small icon-white');
    } else {
        addbutton('Alle anzeigen', dire . 'item/?show=all', 'btn-inverse', 'icon-resize-full icon-white');
    }

    linenav('Dashboard', dire, 'Neuer Artikel hinzuf&uuml;gen', dire . 'item/new/');
        
    ?>
        
        <h1>Artikelliste</h1>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Barcode</th>
                    <th>Kategorie</th>
                    <th>Artikel</th>
                    <th>Status</th>
                    <th class="tableicons"></th>
                </tr>
            </thead>
            <tbody>
        
    <?php
        
    foreach($items as $i) {
    
        if($i['delete']==1) {
            $i['statusname'] = 'Gel&ouml;scht';
        }
        
        $status = $i['statusname'];
        
        if($i['status']==5) {
            $query = mysql_query('SELECT * FROM `packageitem` WHERE `item_id`="'.$i['id'].'" AND `back`="0"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
            $packageitem = mysql_fetch_array($query);
            $query = mysql_query('SELECT * FROM `package` WHERE `id`="'.$packageitem['package_id'].'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
            $package = mysql_fetch_array($query);
            $status = $i['statusname'] . ' - <a href="'.dire.'package/detail/?id='.$package['id'].'">Paket ID '.$package['id'].' - '.$package['customer'];
        } 
    
        echo '
                <tr>
                    <td>'.$i['id'].'</td>
                    <td><a href="'.dire.'barcode/detail/?id='.$i['barcode'].'">'.$i['fullbarcode'].'</a></td>
                    <td><a href="'.dire.'category/?id='.$i['category'].'">'.$i['categoryname'].'</a></td>
                    <td><a href="'.dire.'item/detail/?id='.$i['id'].'">'.$i['name'].'</a></td>
                    <td>' . $status . '</td>
                    <td>' . 
                    gen_right_btn('', dire . 'barcode/print/?id=' . $i['barcode'], 'icon-print icon-white', 'btn btn-mini btn-inverse', 'Barcode drucken', false) .
                    gen_right_btn('', 'edit/?id=' . $i['id'], 'icon-pencil icon-white', 'btn btn-mini btn-warning', 'Artikel bearbeiten', false) .
                    gen_right_btn('', 'delete/?id=' . $i['id'], 'icon-remove icon-white', 'btn btn-mini btn-danger', 'Artikel l&ouml;schen', false) .
                    gen_right_btn('', dire . 'package/new/?item_id=' . $i['id'], 'icon-fire icon-white', 'btn btn-mini btn-primary', 'Paket erstellen', false) . '</td>
                </tr>
            ';
            
    }
    
    echo '
            </tbody>
        </table>
        ';
        
    write_footer();
    
?>