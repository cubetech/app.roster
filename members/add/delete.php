<?
    
    define('dire','../../');
    include(dire.'_env/exec.php');
    
    allowed();
    session_start();
    
    $id = vGET('mid');
    $fid = vGET('fid');
    $_SESSION['modtime'] = time();
    
    if(!$id || $fid<1)
        error();
    
    mysql_query('DELETE FROM `files` WHERE `id`="'.$fid.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        
?>