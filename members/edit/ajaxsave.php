<?
    
    define('dire','../../');
    include(dire.'_env/exec.php');
    
    allowed();
    session_start();

    
    $id = vGET('mid');
    $save = vGET('save');
    
    $data = explode(":::", $save);
    
    if(!$data || count($data)<2)
        error();
        
    if($data[0] == "birthday") {
        $date = explode('.', $data[1]);
        $data[1] = $date[2].'-'.$date[1].'-'.$date[0];
    }
    
    mysql_query('UPDATE `members` SET
                                        `'.$data[0].'` = "'.urldecode($data[1]).'",
                                        `uid` = "'.authed().'"
                WHERE `id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        
?>