<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');

    var_dump(vGET('data'));
    var_dump(vGET('item', true, 'str'));
    
?>