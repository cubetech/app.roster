<?

    define('dire','../../../../');
    include(dire.'_env/exec.php');

    $private = vGET('private');
    
    $query = mysql_query('SELECT * FROM `users` WHERE `uid`="'.authed().'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $member = mysql_fetch_array($query);

    $query = mysql_query('SELECT * FROM `types` WHERE `id`="'.$member['type'].'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $type = mysql_fetch_array($query);

    if(!$private) {
        $query = mysql_query('SELECT count(*) FROM `repository` WHERE `uid`="'.authed().'" AND `status`="public"')
                            or sqlError(__FILE__,__LINE__,__FUNCTION__);
        $publiccount = mysql_fetch_array($query);
        if($type['repo_public']!=-1 && $type['repo_public']<=$publiccount[0])
            error('own','Du hast die Limits f&uuml;r die maximale Anzahl &ouml;ffentlicher Repositories erreicht oder &uuml;berschritten.<br />
                    Die Erstellung von einem weiteren Repository ist fehlgeschlagen.<br /><br />
                    Um ein weiteres Repository anzulegen, l&ouml;sche ein anderes Repository oder w&auml;hle ein anderes Abonnement.');
    } else {
        $query = mysql_query('SELECT count(*) FROM `repository` WHERE `uid`="'.authed().'" AND `status`="private"') 
                            or sqlError(__FILE__,__LINE__,__FUNCTION__);
        $privatecount = mysql_fetch_array($query);
        if($type['repo_private']!=-1 && $type['repo_private']<=$privatecount[0])
            error('own','Du hast die Limits f&uuml;r die maximale Anzahl &ouml;ffentlicher Repositories erreicht oder &uuml;berschritten.<br />
                    Die Erstellung von einem weiteren Repository ist fehlgeschlagen.<br /><br />
                    Um ein weiteres Repository anzulegen, l&ouml;sche ein anderes Repository oder w&auml;hle ein anderes Abonnement.');
    }

    if(svn_repos_create($cfg['subversion']['installpath'].'/asdsfdsfdfd123123')) {
        write_header('repository created');
        echo '  Das Repository wurde erfolgreich angelegt.<br /><br />
                <a href="'.dire.'profile/repo/"><input type="button" value="zur &uuml;bersicht"></a>';
        write_footer();
    } else
        error('own','Die Erstellung des Repositories ist fehlgeschlagen. Bitte verst&auml;ndige den Support.');

?>
