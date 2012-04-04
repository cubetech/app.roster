<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');
    
    $barcode = array();
    $query = mysql_query('SELECT * FROM `barcode`') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    while($fetch=mysql_fetch_array($query))
        array_push($barcode, $fetch);
        
    foreach($barcode as $b) {
        $query = mysql_query('SELECT barcode FROM `item` WHERE `barcode`="'.$b['id'].'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        $item = mysql_fetch_array($query);
        
        if(isset($item) && is_array($item)) {
            continue;
        } else {
            mysql_query('DELETE FROM `barcode` WHERE `id`="'.$b['id'].'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        }
    }
    
    header('Location: '.dire.'barcode/');
    
?>