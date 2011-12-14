<?
    
    define('dire','../../');
    include(dire.'_env/exec.php');

    $id = vGET('id');

    $query = mysql_query('SELECT * FROM `users` WHERE `regkey` = "'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $member = mysql_fetch_array($query);

    if(!$id || !$member)
        error('transfer');

    mysql_query('UPDATE `users` SET 
                    `regkey` = "",
                    `status` = "true"
                    WHERE `uid` = "'.$member['uid'].'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);

    write_header('profile updated');

    echo 'Dein Profil wurde erfolgreich freigeschaltet.<br />
            Du kannst Dich nun einloggen.<br /><br />
            <a href="'.dire.'login/">Zum Login</a>';

    write_footer();

?>
