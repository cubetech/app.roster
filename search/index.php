<?php

    define('dire', '../');
    include(dire . '_env/exec.php');
    
    $search = iGET('search', true, 'string');
    
    if(strlen($search)!=13) {
        error('own', 'Dies ist kein g&uuml;ltiger Barcode in diesem System!');
    }
    
    $query = mysql_query('SELECT * FROM `barcode` WHERE `barcode`="'.$search.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $barcode = mysql_fetch_array($query);

    $query = mysql_query('SELECT * FROM `item` WHERE `barcode`="'.$barcode['id'].'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $item = mysql_fetch_array($query);

    if(!$barcode) {
        error('own', 'Dieser Barcode wurde im System nicht gefunden!');
    }
    
    if(!$item) {
        header('Location: ' . dire . 'barcode/detail/?id=' . $barcode['id']);
    } else {
        header('Location: ' . dire . 'item/detail/?id=' . $item['id']);
    }
    
?>