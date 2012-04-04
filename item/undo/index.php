<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');
    
    $id = vGET('id');
    
    mysql_query('UPDATE `item` SET `delete`=0 WHERE `id`="' . $id . '"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    
    header('Location: ../detail/?id=' . $id);
    
?>