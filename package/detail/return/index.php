<?php

    define('dire', '../../../');
    include(dire . '_env/exec.php');
    
    $id = iGET('id');
    $pid = iGET('pid');
    
    mysql_query('UPDATE `item` SET `status`=1 WHERE `id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    mysql_query('UPDATE `packageitem` SET `back`="1" WHERE `package_id`="'.$pid.'" AND `item_id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    
    header('Location: ../?id=' . $pid);
    
?>