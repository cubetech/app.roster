<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');
    
    $id = vGET('id');
    $customer_id = vGET('customer_id');
    if($customer_id!='') {
        $custid = '?customer_id=' . $customer_id;
    }
        
    $query = mysql_query('SELECT * FROM `package` WHERE `id` = "'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $package = mysql_fetch_array($query);
    
    if(!$package) {
        error('transfer');
    }
    
    $delete = 0;
    
    if($package['delete']==0) {
        $delete = 1;
    }
    
    mysql_query('UPDATE `package` SET `delete`="'.$delete.'" WHERE `id`=' . $id) or sqlError(__FILE__,__LINE__,__FUNCTION__);
    
    if($delete == 1) {
        set_message('Gel&ouml;scht!', 'Das Paket mit der <strong>ID ' . $id . '</strong> wurde gel&ouml;scht.
        <a href="delete/?id=' . $id . '">R&uuml;ckg&auml;ngig</a>', 'alert-warning');
        header('Location: ../' . @$custid);
    } else {
    set_message('Wiederhergestellt!', 'Das Paket mit der <strong>ID ' . $id . '</strong> wurde wiederhergestellt.', 'alert-success');
        header('Location: ../' . @$custid);
        #header('Location: ../detail/?id=' . $id);
    }
    
?>