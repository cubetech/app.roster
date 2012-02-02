<?

    define('dire','../../');
    include(dire.'_env/exec.php');

    session_start();

    $username = vGET('username');

    $query = mysql_query('SELECT * FROM `users` WHERE `username` = "'.$username.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $member = mysql_fetch_array($query);

    if(!$member)
        error('own','member');

    sendMail($member['mail'],$username,'Deine Registration auf svnhost.ch',
'Hallo '.$username.'
Du hast Dich erfolgreich auf svnhost.ch registriert.
Um Deine Registrierung abzuschliessen klicke bitte hier:
http://www.svnhost.ch/signup/confirm/?id='.$member['regkey'].'
Viele Gruesse
Dein svnhost.ch-Team

Diese Mail wurde automatisch generiert.');

    write_header('Mail sent');

    echo 'Das Best&auml;tigungsmail wurde erneut an Deine Mailadresse versendet.';

    write_footer();

?>
