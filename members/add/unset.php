<?
    
    define('dire','../../');
    include(dire.'_env/exec.php');

    allowed();
    session_start();
    
    $unset = vGET('unset');
    if($unset=='true') {
        session_destroy();
    }

    header('Location: ./');
    
?>