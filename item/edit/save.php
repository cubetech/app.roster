<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');

    $data = vGET('data');
    $id = vGET('id');
    
    $result = gen_update_query($data);
    $query = 'UPDATE item SET ';
        
    mysql_query($query . $result . ' WHERE `id`="'.$id.'"') OR sqlError(__FILE__,__LINE__,__FUNCTION__);
    
    $custom = vGET('custom');
    
    foreach($custom as $name => $value) {
    
        $query = mysql_query('SELECT * FROM `customfield` WHERE `name`="'.$name.'"') OR sqlError(__FILE__,__LINE__,__FUNCTION__);
        $field = mysql_fetch_array($query);
        
        $query = mysql_query('SELECT * FROM `customcontent` WHERE `field_id`="'.$field['id'].'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        $cfield = mysql_fetch_array($query);
                
        if(isset($value) && $value!='') {
        
            if(!is_array($cfield)) {
                mysql_query('INSERT INTO `customcontent` (field_id, value_id, value) VALUES ("'.$field['id'].'", "'.$value_id.'", "'.$value.'")') OR sqlError(__FILE__,__LINE__,__FUNCTION__);
            } else {
                mysql_query('UPDATE `customcontent` SET `value`="'.$value.'" WHERE `id`="'.$cfield['id'].'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
            }
            
        }
        
    }
    
    $category = vGET('category');
    mysql_query('DELETE FROM `categoryitem` WHERE `item_id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    
    if(is_array($category)) {
        foreach($category as $c) {
            mysql_query('INSERT INTO `categoryitem` (category_id, item_id) VALUES ("'.$c.'", "'.$id.'")') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        }
    }

    header('Location: '.dire.'item/detail/?id=' . $id);

?>