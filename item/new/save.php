<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');
    
    //header('Location: ./');
    //die();

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
        
        if(isset($value) && $value!='') {
            mysql_query('INSERT INTO `customcontent` (field_id, value_id, value) VALUES ("'.$field['id'].'", "'.$value_id.'", "'.$value.'")') OR sqlError(__FILE__,__LINE__,__FUNCTION__);
        }
        
    }
    
    $category = vGET('category');
    if(is_array($category)) {
        foreach($category as $c) {
            mysql_query('INSERT INTO `categoryitem` (category_id, item_id) VALUES ("'.$c.'", "'.$value_id.'")') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        }
    }

    header('Location: '.dire.'item/detail/?id=' . $value_id);

?>