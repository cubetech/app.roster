<?
    
    define('dire','../../');
    include(dire.'_env/exec.php');
    
    allowed();
    
    $id = vGET('id');
    
    if(!$id)
        error('transfer');
    
    mysql_query('DELETE FROM `members` WHERE `id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    
    header('Location: '.dire.'members/');
        
?>