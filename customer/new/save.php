<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');

    
    $client = vGET('client');
    
    $result = gen_query($client);

    $query = 'INSERT INTO `customer` ';
    
    mysql_query($query . $result['names'] . ') VALUES ' . $result['values'] . ')') OR sqlError(__FILE__,__LINE__,__FUNCTION__);
    $customer_id = mysql_insert_id();

    header('Location: ../detail/?id=' . $customer_id);

?>