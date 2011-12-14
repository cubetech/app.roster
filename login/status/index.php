<?

    define('dire','../../');
    include(dire.'_env/exec.php');

    $username = vGET('username');
    
    auth_set_login($username,'');
    auth_start();

    write_header('Login failed');

    echo 'Du hast Dein Profil noch nicht best&auml;tigt, deshalb kannst Du Dich noch nicht einloggen.<br /><br />
        <a href="./send.php">Best&auml;tigungsmail nochmals zusenden</a>';

    write_footer();

?>
