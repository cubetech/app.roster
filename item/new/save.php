<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');

    $data = vGET('data');
    if(!@$data['barcode'] || @$data['barcode']=='') {
        $data['barcode'] = code13();
    }
    
    $result = gen_query($data);
    $query = 'INSERT INTO item ';
    
    mysql_query($query . $result['names'] . ') VALUES ' . $result['values'] . ')') OR sqlError(__FILE__,__LINE__,__FUNCTION__);
    $value_id = mysql_insert_id();
    
    $custom = vGET('custom');
    
    foreach($custom as $name => $value) {
    
        $query = mysql_query('SELECT * FROM `customfield` WHERE `name`="'.$name.'"') OR sqlError(__FILE__,__LINE__,__FUNCTION__);
        $field = mysql_fetch_array($query);
        
        mysql_query('INSERT INTO `customcontent` (field_id, value_id, value) VALUES ("'.$field['id'].'", "'.$value_id.'", "'.$value.'")') OR sqlError(__FILE__,__LINE__,__FUNCTION__);
        
    }
    
    header('Location: '.dire.'item/detail/?id=' . $value_id);

?>