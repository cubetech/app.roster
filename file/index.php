<?

    define('dire','../');
    include(dire.'_env/exec.php');

    $file = vGET('file');
    $dir = vGET('dir');

    session_start();
    $id = vGET('id');

    $query = mysql_query('SELECT * FROM `repository` WHERE `id` = "'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $repo = mysql_fetch_array($query);

    if($repo['uid']!=authed())
        error('transfer');
    
    $fileEnd = fileCheck($file);

    if($fileEnd=='clear') {

        write_header('File ansehen');
    
        echo '<a href="javascript: history.back();">Zur&uuml;ck</a><br /><br />';

        if(@svn_cat('file://'.$cfg['subversion']['installpath'].'/'.$repo['name'].'/'.$file))
            $filec = svn_cat('file://'.$cfg['subversion']['installpath'].'/'.$repo['name'].'/'.$file);
        else
            error('transfer');

        echo '<div style="background-color: #F8F8F8; border: 1px solid #888888;">'.@str_replace('&nbsp;', ' ', highlight_string($filec, true)).'</div>';

        write_footer();

    }
    else {

        $fileext = explode('/',$file);
        $fileext = $fileext[(count($fileext)-1)];

        $mm_type="application/octet-stream";

        header("Cache-Control: public, must-revalidate");
        header("Pragma: hack");
        header("Content-Type: " . $mm_type);
        header('Content-Disposition: attachment; filename="'.$fileext.'"');
        header("Content-Transfer-Encoding: binary\n");

        print svn_cat('file://'.$cfg['subversion']['installpath'].'/'.$repo['name'].'/'.$file);

    }

?>
