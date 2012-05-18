<?php

    define('dire', '../');
    include(dire . '_env/exec.php');
    
    $code = vGET('barcode', true, 'str');
    $id = vGET('id');
    $pid = vGET('pid', true, 'str');
    $piid = vGET('piid', true, 'str');
    
    if(isset($code) && $code!='') {
        $query = mysql_query('SELECT * FROM `barcode` WHERE `barcode` = "'.$code.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        $barcode = mysql_fetch_array($query);
    } else {
        $query = mysql_query('SELECT * FROM `barcode` WHERE `id` = "'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        $barcode = mysql_fetch_array($query);
    }
        
    if(!isset($barcode) || !$barcode) {
        error('transfer');
    }
        
    $query = mysql_query('SELECT * FROM `item` WHERE `barcode`="'.$barcode['id'].'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $item = mysql_fetch_array($query);
        
    $query = mysql_query('SELECT * FROM `packageitem` WHERE `item_id`="'.$item['id'].'" AND `back`="0"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $packageitem = mysql_fetch_array($query);
    
    if($packageitem) {
    
       mysql_query('UPDATE `item` SET `status`=1 WHERE `id`="'.$item['id'].'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
       mysql_query('UPDATE `packageitem` SET `back`="1", `back_ts` = UNIX_TIMESTAMP() WHERE `package_id`="'.$packageitem['package_id'].'" AND `item_id`="'.$item['id'].'" AND id="'.$piid.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);

        $pkgs = array();
        $query = mysql_query('SELECT * FROM `packageitem` WHERE `package_id`="'.$packageitem['package_id'].'" AND `back`="0"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        while($fetch=mysql_fetch_array($query))
            array_push($pkgs, $fetch);
            
        if(count($pkgs)==0) {
            mysql_query('UPDATE `package` SET `status`="8", `returndate`="'.time().'" WHERE `id`="'.$packageitem['package_id'].'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
            $msg =  'Das Paket mit der <strong>ID ' . $packageitem['package_id'] . '</strong> hat keine verbleibenden Artikel und wurde zur&uuml;ckgebucht.';
        } else {
            $msg = ' Das Paket mit der <strong><a href="'.dire.'package/detail/?id='.$packageitem['package_id'].'">ID ' . $packageitem['package_id'] . '</a></strong> hat '.(count($pkgs)).' verbleibende Artikel.';
        }
        
        set_message('Erledigt!', 'Der Artikel mit der <strong>ID ' . $item['id'] . '</strong> wurde zur&uuml;ckgebucht.' . $msg, 'alert-success');
        
    }
    
    if(!$pid || $pid=='') {
        header('Location: ./');
    } else {
        header('Location: '.dire.'package/detail/?id=' . $pid);
    }
    
?>