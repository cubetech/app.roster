<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');
    
    $id = vGET('id');
    
    mysql_query('UPDATE `item` SET `delete`=1 WHERE `id`=' . $id) or sqlError(__FILE__,__LINE__,__FUNCTION__);
                                    
    header('Location: ../?del=' . $id);
    
?>