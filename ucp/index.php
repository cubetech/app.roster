<?
    
    define('dire','../');
    include(dire.'_env/exec.php');

    allowed();
    session_start();

    $uid = authed();
    if(!$uid)
        error('allowed');

    $query = mysql_query('SELECT * FROM `users` WHERE users.uid = "'.$uid.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $member = mysql_fetch_array($query);

    $sidebar = '
        <a href="#"><img src="'.$tmp_style_path.'icons/comment_edit.png"> edit your data</a><br />
        <a href="'.dire.'ucp/password/"><img src="'.$tmp_style_path.'icons/comment_edit.png"> change password</a><br />
    ';
    
    write_header('your profile');

    echo '
        <table style="border: 1px solid #DDD; width: 100%;">
            <tr>
                <td style="text-align: right; vertical-align: top; padding-right: 10px; padding-bottom: 1.5em; width: 140px;">
                    <h2>'.$member['username'].'</h2>
                </td>
                <td class="col_right">
                    <b>'.$member['mail'].'</b>
                </td>
            </tr>
            <tr>
                <td class="col_left">
                    Vorname:
                </td>
                <td class="bold">
                    '.$member['prename'].'
                </td>
            </tr>
            <tr>
                <td class="col_left">
                    Name:
                </td>
                <td class="bold">
                    '.$member['name'].'
                </td>
            </tr>
            <tr>
                <td class="col_left">
                    Mail:
                </td>
                <td class="bold">
                    <a href="mailto:'.$member['mail'].'">'.$member['mail'].'</a>
                </td>
            </tr>
        </table><br />
        ';
    
    write_footer();

?>
