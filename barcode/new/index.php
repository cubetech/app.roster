<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');
    
    $id = vGET('item_id');
    
    $code = code13();

    mysql_query('UPDATE `item` SET `barcode`="'.$code.'" WHERE `id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    
    header('Location: '.dire.'barcode/detail/?id=' . $code);
    
?>