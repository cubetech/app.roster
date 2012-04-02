<?php

    define('dire', '../');
    
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
    if (!$code) header('Location: '.dire);
    
    barcode_print($code,getvar('encoding'),getvar('scale'),getvar('mode'));

?>