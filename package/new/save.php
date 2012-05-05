<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');

    $data = vGET('data');
    $data['startdate'] = explode('.', $data['startdate']);
    $data['startdate'] = mktime(0,0,0,$data['startdate'][1],$data['startdate'][0],$data['startdate'][2]);
    $data['duedate'] = explode('.', $data['duedate']);
    $data['duedate'] = mktime(0,0,0,$data['duedate'][1],$data['duedate'][0],$data['duedate'][2]);
    $items = vGET('item', true, 'str');
    
    $query = mysql_query('INSERT INTO `package` (name, startdate, duedate, customer, person, status) VALUES ("'.$data['packagename'].'", "'.$data['startdate'].'", "'.$data['duedate'].'", "'.$data['customer'].'", "'.$data['person'].'", "'.$data['status'].'")') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $package_id = mysql_insert_id();

    if(@is_array($items)) {
    
        foreach($items as $i) {
        
            $query = mysql_query('INSERT INTO `packageitem` (package_id, item_id) VALUES ("'.$package_id.'", "'.$i.'")') or sqlError(__FILE__,__LINE__,__FUNCTION__);
            $query = mysql_query('UPDATE `item` SET status="5" WHERE `id`="'.$i.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
            
        }
        
    }
    
    header('Location: ../detail/?id='.$package_id);
    
?>