<?
    
    define('dire','../../');
    include(dire.'_env/exec.php');

    allowed();
    session_start();

    $uid = authed();
    if(!$uid)
        error('allowed');

    $query = mysql_query('SELECT * FROM `users` WHERE users.uid = "'.$uid.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $member = mysql_fetch_array($query);

    $sidebar = '
        <a href="../"><img src="'.$tmp_style_path.'icons/comment_edit.png"> back to overview</a><br />
        <a href="#"><img src="'.$tmp_style_path.'icons/comment_edit.png"> edit your data</a><br />
        <a href="'.dire.'ucp/password/"><img src="'.$tmp_style_path.'icons/comment_edit.png"> change password</a><br />
    ';
    
    write_header('change password');

    echo '
        <form action="save.php" method="POST">
        <table style="border: 1px solid #DDD; width: 100%;">
            <tr>
                <td style="text-align: right; vertical-align: top; padding-right: 10px; padding-bottom: 1.5em; width: 140px;">
                    <h2>Passwort &auml;ndern</h2>
                </td>
                <td class="col_right">
                    <b></b>
                </td>
            </tr>
            <tr>
                <td class="col_left">
                    Altes Passwort:
                </td>
                <td class="bold">
                    <input type="password" name="password">
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="col_left">
                    Neues Passwort:
                </td>
                <td class="bold">
                    <input type="password" name="newpassword">
                </td>
            </tr>
            <tr>
                <td class="col_left">
                    Passwort wiederholen:
                </td>
                <td class="bold">
                    <input type="password" name="newpassword2">
                </td>
            </tr>
            <tr>
                <td class="col_left">
                </td>
                <td class="small">
                    Minimale L&auml;nge: 6 Zeichen
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="col_left">
                </td>
                <td class="small">
                    <input type="submit" value="Speichern &raquo;">
                </td>
            </tr>
        </table></form><br />
        ';
    
    write_footer();

?>
