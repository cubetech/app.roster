<?

    error_reporting(E_ALL);
    ini_set('error_log',dire.'_env/error.txt');
    ini_set('log_errors',true);
    setlocale(LC_TIME, "de_CH");
    session_start();

    include(dire.'_env/class.session.php');
    include(dire.'_env/config.php');
    include(dire.'_env/functions.php');
    include(dire.'_env/func_auth.php');
    include(dire.'_env/func_box.php');
    include(dire.'_env/func_domain.php');
    include(dire.'_env/func_error.php');
    include(dire.'_env/func_header.php');
    include(dire.'_env/func_image.php');
    include(dire.'_env/func_message.php');
    include(dire.'_env/func_mysql.php');
    include(dire.'_env/func_profile.php');
    include(dire.'_env/func_server.php');
    include(dire.'_env/func_user.php');

    mdb_connect();
    auth_start();
    
    $tmp_style_path = dire.$cfg['style']['path'].'/'.$cfg['style']['id'].'/';
    $sidebar = '';

	#if(!function_exists('svn_info'))
    #    error('own','Die PHP-Subversion-Extension ist auf diesem Server nicht installiert. Das System kann nicht verwendet werden. <br/><br />
    #    <b>Anleitung</b><br />
    #    Zuerst muss via <code>sudo aptitude install php-pear php5-dev libsvncpp-dev</code> einige Pakete nachinstalliert werden<br />
    #    Danach mit <code>sudo pecl install svn-0.5.0</code> die Subversion-Extension installieren<br />
    #    Nun muss in der php.ini noch die svn.so-Extension eingetragen werden und der Apache ein mal neugestartet werden.<br />
    #    Jetzt sollte diese Meldung nicht mehr auftauchen.        
    #    ');
    
    header('Content-Type: text/html; charset=iso-8859-1');
?>