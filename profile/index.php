<?
    
    define('dire','../');
    include(dire.'_env/exec.php');

    allowed();
    session_start();

    $uid = vGET('uid');
    if(!$uid && !isset($_SESSION['uid']))
        error('transfer');
    elseif($uid>0 && !isset($_SESSION['uid']) || $uid>0 && $_SESSION['uid']!=$uid)
        $_SESSION['uid'] = $uid;

    $query = mysql_query('SELECT * FROM `users` WHERE users.uid = "'.$uid.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $member = mysql_fetch_array($query);

    $sidebar = '
        <a href="#"><img src="'.$tmp_style_path.'icons/comment_edit.png"> edit member</a><br />
    ';
    
    write_header('details user <b>'.$member['username'].'</b>');

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
