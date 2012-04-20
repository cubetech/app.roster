<?php

    define('dire', '../');
    include(dire . '_env/exec.php');
    
    $customer = array();
    $query = mysql_query('SELECT c.*,
                                 (SELECT COUNT(*) FROM package WHERE customer_id=c.id) as package
                            FROM customer c 
                            ORDER BY c.id') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    while($fetch=mysql_fetch_array($query))
        array_push($customer, $fetch);
        
    write_header('Kunden');
    
    linenav('Dashboard', dire, 'Neuer Kunde erstellen', dire . 'customer/new/');
    
    ?>
        
        <h1>Kunden</h1>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Firma</th>
                    <th>Name</th>
                    <th>Ort</th>
                    <th>Pakete</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
        
    <?php
        
    foreach($customer as $c) {
    
        echo '
                <tr>
                    <td>'.$c['id'].'</td>
                    <td><a href="'.dire.'customer/detail/?id='.$c['id'].'">'.$c['company'].'</a></td>
                    <td><a href="'.dire.'customer/detail/?id='.$c['id'].'">'.$c['prename'].' '.$c['name'].'</a></td>
                    <td>' . $c['location'] . '</td>
                    <td><a href="'.dire.'package/?customer_id='.$c['id'].'">' . $c['package'] . '</a></td>
                    <td>' .
                    gen_right_btn('', 'edit/?id=' . $c['id'], 'icon-pencil icon-white', 'btn btn-mini btn-warning', 'Kunde bearbeiten', false) .
                    gen_right_btn('', 'delete/?id=' . $c['id'], 'icon-remove icon-white', 'btn btn-mini btn-danger', 'Kunde l&ouml;schen', false) .
                    gen_right_btn('', dire . 'package/new/?customer_id=' . $c['id'], 'icon-fire icon-white', 'btn btn-mini btn-primary', 'Paket erstellen', false) . '</td>
                </tr>
            ';
            
    }
    
    echo '
            </tbody>
        </table>
        ';
        
    write_footer();
    
?>