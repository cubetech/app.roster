<?php

    error_reporting(E_ALL);
    ini_set('error_log',dire.'_env/error.txt');
    ini_set('log_errors',true);
    setlocale(LC_TIME, "de_CH");       
    session_start();
    include(dire.'_env/class.session.php');
    include(dire.'_env/config.php');

    require_once(dire . '_env/access.php');

    include(dire.'_env/functions.php');
    include(dire.'_env/func_auth.php');     
    include(dire.'_env/func_header.php');
    include(dire.'_env/func_message.php');
    include(dire.'_env/func_mysql.php');
    include(dire.'_env/func_style.php');
    include(dire.'_env/func_user.php');

    autoinclude(dire . $cfg['page']['autoinclude']);
    mdb_connect();
    auth_start();
    
    $tmp_style_path = dire.$cfg['style']['path'].'/'.$cfg['style']['id'].'/';
    $sidebar = '';

    header('Content-Type: text/html; charset=iso-8859-1');
?>