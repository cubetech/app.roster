<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');
    
    $id = vGET('id');
    
    $query = mysql_query('SELECT * FROM `customer` WHERE `id` = "'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $customer = mysql_fetch_array($query);
    
    $query = mysql_query('SELECT p.*,
                                s.grade as grade
                                FROM package p
                                LEFT JOIN
                                status s ON (p.status = s.id)
                                WHERE grade="active"
                                AND p.customer_id="'.$id.'"
                                AND p.delete!="1"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $package = mysql_fetch_array($query);
    if(is_array($package)) {
        error('own', 'Dieser Kunde hat noch aktive Pakete! Bitte erst l&ouml;schen oder zur&uuml;kbuchen.<br /><br /><a href="'.dire.'package/?customer_id='.$id.'">Aktive Pakete anzeigen</a>');
    }
    
    if(!$customer) {
        error('transfer');
    }
    
    $delete = 0;
    
    if($customer['delete']==0) {
        $delete = 1;
    }
    
    mysql_query('UPDATE `customer` SET `delete`="'.$delete.'" WHERE `id`=' . $id) or sqlError(__FILE__,__LINE__,__FUNCTION__);
    
    if($delete == 1) {
        set_message('Gel&ouml;scht!', 'Der Kunde mit der <strong>ID ' . $id . '</strong> wurde gel&ouml;scht.
        <a href="delete/?id=' . $id . '">R&uuml;ckg&auml;ngig</a>', 'alert-warning');
        header('Location: ../');
    } else {
    set_message('Wiederhergestellt!', 'Der Kunde mit der <strong>ID ' . $id . '</strong> wurde wiederhergestellt.', 'alert-success');
        header('Location: ../detail/?id=' . $id);
    }
    
?>