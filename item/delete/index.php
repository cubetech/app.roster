<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');
    
    $id = vGET('id');
    
    $query = mysql_query('SELECT * FROM `item` WHERE `id` = "'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $item = mysql_fetch_array($query);
    
    if(!$item) {
        error('transfer');
    }
    
    $delete = 0;
    
    if($item['delete']==0) {
        $delete = 1;
    }
    
    mysql_query('UPDATE `item` SET `delete`="'.$delete.'" WHERE `id`=' . $id) or sqlError(__FILE__,__LINE__,__FUNCTION__);
    
    if($delete == 1) {
        set_message('Gel&ouml;scht!', 'Der Artikel mit der <strong>ID ' . $id . '</strong> wurde gel&ouml;scht.
        <a href="delete/?id=' . $id . '">R&uuml;ckg&auml;ngig</a>', 'alert-warning');
        header('Location: ../');
    } else {
    set_message('Wiederhergestellt!', 'Der Artikel mit der <strong>ID ' . $id . '</strong> wurde wiederhergestellt.', 'alert-success');
        header('Location: ../detail/?id=' . $id);
    }
    
?>