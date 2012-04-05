<?php

    define('dire', '../');
    include(dire . '_env/exec.php');
    
    require(dire . '_env/addons/php-barcode/php-barcode.php');

    function getvar($name){
        global $_GET, $_POST;
        if (isset($_GET[$name])) return $_GET[$name];
        else if (isset($_POST[$name])) return $_POST[$name];
        else return false;
    }
    
    if (get_magic_quotes_gpc()){
        $code=stripslashes(getvar('code'));
    } else {
        $code=getvar('code');
    }
    if ($code) {
        barcode_print($code,getvar('encoding'),getvar('scale'),getvar('mode'));
    } else {
    
        $barcode = array();
        $query = mysql_query('SELECT * FROM `barcode`');
        while($fetch=mysql_fetch_array($query))
            array_push($barcode, $fetch);
        $count = count($barcode);
    
        write_header('Barcodes');
        
        linenav('Dashboard', dire, 'Barcodes aufr&auml;umen', dire . 'barcode/cleanup/', 'icon-chevron-left', 'icon-fire icon-white');
        
        ?>
            
            Es sind aktuell <strong><?=$count?> Barcodes</strong> vorhanden.
            <br /><br />
    
        <?
        
        foreach($barcode as $b) {
            $query = mysql_query('SELECT * FROM `item` WHERE `barcode`="'.$b['id'].'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
            $item = mysql_fetch_array($query);
            
            $class = ' error';
            if(is_array($item)) {
                $class = ' valid';
            }
            
            echo '<a title="Barcode-Details anzeigen" href="detail/?id=' . $b['id'] . '"><img src="' . dire . 'barcode/?code=' . $b['barcode'] . '" alt="barcode" class="imgborder ' . $class . '" /></a>';
        }
        
        write_footer();
        
    }

?>