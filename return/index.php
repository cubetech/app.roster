<?php

    define('dire', '../');
    include(dire . '_env/exec.php');
    
    $title = 'Artikelr&uuml;ckgabe';
    
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
                            WHERE i.status="5" AND i.delete="0"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $items = array();
    while($fetch=mysql_fetch_array($query))
        array_push($items, $fetch);
    
    write_header($title);
        
    linenav('Dashboard', dire);
    
    ?>
        
        <h1><?=$title?></h1>
        
        <hr />
        
        <form action="return.php" method="post">
            <input type="text" name="barcode" id="barcode" />
            <input type="submit" value="R&uuml;ckgabe" />
        </form>
        
        <h2>Ausgeliehene Artikel</h2>
    
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Barcode</th>
                    <th>Kategorie</th>
                    <th class="big5">Artikel</th>
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
    
        echo '
                <tr>
                    <td>'.$i['id'].'</td>
                    <td><a href="'.dire.'barcode/detail/?id='.$i['barcode'].'">'.$i['fullbarcode'].'</a></td>
                    <td><a href="'.dire.'category/?id='.$i['category'].'">'.$i['categoryname'].'</a></td>
                    <td><a href="'.dire.'item/detail/?id='.$i['id'].'">'.$i['name'].'</a></td>
                    <td>' . $i['statusname'] . '</td>
                    <td>' . 
                    gen_right_btn('', 'javascript:if(confirm(\'Sind Sie sicher?\')) { window.location = \'./return.php?barcode=' . $i['fullbarcode'] . '\'; }', 'icon-refresh icon-white', 'btn btn-mini btn-warning" id="'.$i['id'].'', 'Artikel zur&uuml;ckbuchen', false) .
                    '</td>
                </tr>
            ';
            
    }
    
    echo '
            </tbody>
        </table>
        ';
      
    write_footer();
    
?>