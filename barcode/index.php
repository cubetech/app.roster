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
    
        $query = mysql_query('SELECT COUNT(*) FROM `barcode`');
        $count = mysql_fetch_row($query);
    
        write_header('Barcodes');
        
        linenav('Dashboard', dire, 'Barcodes aufr&auml;umen', dire . 'barcode/cleanup/');
        
        ?>
            
            Es sind aktuell <strong><?=$count[0]?> Barcodes</strong> vorhanden.
            
        <?
        
        write_footer();
        
    }

?>