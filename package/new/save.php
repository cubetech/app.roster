<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');

    $data = vGET('data');
    $data['date'] = explode('.', $data['datepicker']);
    $items = vGET('item', true, 'str');
    $customer = vGET('customer', true, 'str');
    
    if($data['hidden']==false || $customer=='manual') {
        
        $client = vGET('client');
        
        $result = gen_query($client);
        $query = 'INSERT INTO `customer` ';
        
        mysql_query($query . $result['names'] . ') VALUES ' . $result['values'] . ')') OR sqlError(__FILE__,__LINE__,__FUNCTION__);
        $customer_id = mysql_insert_id();
        
    } else {
    
        $customer_id = $customer;
        
    }
    
    $query = mysql_query('INSERT INTO `package` (name, duedate, customer_id, status) VALUES ("'.$data['packagename'].'", "'.mktime(0,0,0,$data['date'][1],$data['date'][0],$data['date'][2]).'", "'.$customer_id.'", "'.$data['status'].'")') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $package_id = mysql_insert_id();

    if(@is_array($items)) {
    
        foreach($items as $i) {
        
            $query = mysql_query('INSERT INTO `packageitem` (package_id, item_id) VALUES ("'.$package_id.'", "'.$i.'")') or sqlError(__FILE__,__LINE__,__FUNCTION__);
            $query = mysql_query('UPDATE `item` SET status="5" WHERE `id`="'.$i.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
            
        }
        
    }
    
    header('Location: ../detail/?id='.$package_id);
    
?>