<?

    define('dire','../../../');
    include(dire.'_env/exec.php');

    session_start();
    
    $id = vGET('id');
    $username = vGET('username');
    $password = vGET('password');
    $password2 = vGET('password2');
    $mail = vGET('mail');

    $query = mysql_query('SELECT * FROM `users` WHERE `username`="'.$username.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $member = mysql_fetch_array($query);
    $error = array();
    if($member)
        $error['username'] = 'error';

    $query = mysql_query('SELECT * FROM `users` WHERE `mail`="'.$mail.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $mailmember = mysql_fetch_array($query);

    if($mailmember)
        $error['mail'] = 'error';

    if(!eregi("([_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,}))",$mail))
        $error['mail'] = 'false';

    if(strlen($password)<8)
        $error['password'] = 'length';

    if($password!=$password2)
        $error['password'] = 'error';

    if(htmlentities($password)!=$password)
        $error['password'] = 'false';

/*    if(isset($error['username']) || isset($error['mail'])) {
        $_SESSION['username'] = $username;
        $_SESSION['mail'] = $mail;
        $_SESSION['error'] = $error;
        $_SESSION['id'] = $id;
        $_SESSION['password'] = $password;
        $_SESSION['password2'] = $password2;
        header('Location: ../');
    }
*/
    $regkey = rand(100000000000,999999999999);

    mysql_query('INSERT INTO `users` 
                (`username`,`password`,`mail`,`regkey`) 
                VALUES 
                ("'.$username.'","'.$password.'","'.$mail.'","'.$regkey.'")') 
                or sqlError(__FILE__,__LINE__,__FUNCTION__);

    sendMail($mail,$username,'Deine Registration auf svnhost.ch',
'Hallo '.$username.'
Du hast Dich erfolgreich auf svnhost.ch registriert.
Um Deine Registrierung abzuschliessen klicke bitte hier:
http://www.svnhost.ch/signup/confirm/?id='.$regkey.'
Viele Gruesse
Dein svnhost.ch-Team

Diese Mail wurde automatisch generiert.');

    write_header('Sign Up successful');

    echo 'Die Registration war erfolgreich. Um den Vorgang abzuschliessen &uuml;berfr&uuml;fe bitte Deine Mails und folge den Anweisungen im Mail.';

    write_footer();

?>
