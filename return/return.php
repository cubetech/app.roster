<?php

    define('dire', '../');
    include(dire . '_env/exec.php');
    
    $barcode = iGET('barcode', true, 'str');
    
    if(strlen($barcode)!=13) {
        error('own', 'Dies ist kein g&uuml;ltiger Barcode in diesem System! Es wird ein EAN13 Code erwartet.');
    }
    
    $query = mysql_query('SELECT * FROM `barcode` WHERE `barcode` = "'.$barcode.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $barcode = mysql_fetch_array($query);
        
    $query = mysql_query('SELECT * FROM `item` WHERE `barcode`="'.$barcode['id'].'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $item = mysql_fetch_array($query);
        
    $query = mysql_query('SELECT * FROM `packageitem` WHERE `item_id`="'.$item['id'].'" AND `back`="0"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $packageitem = mysql_fetch_array($query);
    
    if($packageitem) {
    
        mysql_query('UPDATE `item` SET `status`=1 WHERE `id`="'.$item['id'].'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        mysql_query('UPDATE `packageitem` SET `back`="1" WHERE `package_id`="'.$packageitem['package_id'].'" AND `item_id`="'.$item['id'].'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        
        set_message('Erledigt!', 'Der Artikel mit der <strong>ID ' . $item['id'] . '</strong> wurde zur&uuml;ckgebucht.', 'alert-success');
        
    }
    
    header('Location: ./');
    
?>