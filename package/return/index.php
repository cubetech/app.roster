<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');
    
    $id = iGET('id');
    
    $item = array();
    $query = mysql_query('SELECT * FROM `packageitem` WHERE `package_id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    while($fetch=mysql_fetch_array($query))
        array_push($item, $fetch);
        
    foreach($item as $i) {
        mysql_query('UPDATE `item` SET `status`=1 WHERE `id`="'.$i['item_id'].'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    }
        
    mysql_query('UPDATE `package` SET `status`=8 WHERE `id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    mysql_query('UPDATE `packageitem` SET `back`="1" WHERE `package_id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    
    set_message('Erledigt!', 'Das Paket mit der <strong>ID ' . $id . '</strong> wurde zur&uuml;ckgebucht.', 'alert-success');
    header('Location: ../?id=' . $id);
    
?>