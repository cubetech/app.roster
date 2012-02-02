<?

    define('dire','../');
    include(dire.'_env/exec.php');

    if(authed())
        header("Location: logout.php");

    write_header('Anmelden');

    echo '
        Bitte gib Deine Zugangsdaten ein:<br /><br />
        <form action="./login.php" method="post">
            <div style="width: 100px; float: left; padding-top: 3px;">Username:</div>
            <input type="text" name="username" />
            <br />
            <div style="width: 100px; float: left; padding-top: 3px; margin-top: 10px;">Passwort:</div>
            <input type="password" name="password" style="margin-top: 10px;" />
            <br /><br />
            <input type="submit" value="Anmelden" />
        </form>';

    write_footer();

?>
