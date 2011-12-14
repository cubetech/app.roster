<?
    
    define('dire','../../');
    include(dire.'_env/exec.php');
    
    allowed();
    session_start();
    
    $id = vGET('id');
    $fid = vGET('fid');
    
    if(!$id || $fid<1)
        error();
    
    mysql_query('UPDATE `files` SET `mid`="0"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    
    header('Location: '.dire.'members/details/?id='.$id);
        
?>