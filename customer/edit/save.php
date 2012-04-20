<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');

    $customer = vGET('customer');
    
    $result = gen_update_query($customer);

    $query = 'UPDATE `customer` SET ';
    
    mysql_query($query . $result . ' WHERE `id`="'.$customer['id'].'"') OR sqlError(__FILE__,__LINE__,__FUNCTION__);

    header('Location: ../');

?>