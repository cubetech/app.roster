<?
    
    define('dire','../');
    include(dire.'_env/exec.php');
  
    auth_set_login('','');

    auth_start();

    write_header('ausgeloggt');

    echo showBox('
        Du hast Dich ausgeloggt.<br><br>
        <a href="'.dire.'"><input type="button" value="Startseite"></a>
    ');

    write_footer();

?>
