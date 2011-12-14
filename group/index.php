<?
    
    define('dire','../');
    include(dire.'_env/exec.php');

    allowed();
    session_start();

    $id = vGET('id');
    if(!$id && !isset($_SESSION['gid']))
        error('transfer');
    elseif($id>0 && !isset($_SESSION['gid']) || $id>0 && $_SESSION['gid']!=$id)
        $_SESSION['gid'] = $id;

    $query = mysql_query('SELECT COUNT(*) FROM `device` WHERE gid="'.$_SESSION['gid'].'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $devicecount = mysql_fetch_array($query);
    
    $page = vGET('page');
    
    if($page>0) {
        if(ceil($devicecount[0]/$cfg['page']['pagesteps'])<$page)
            $page = 1; 
        if($page>1) {
            $start = (($page-1)*$cfg['page']['pagesteps']);
            $end = $cfg['page']['pagesteps'];
        } else {
            $start = 0;
            $end = $cfg['page']['pagesteps'];
        }
    } else {
        $start = 0;
        $end = $cfg['page']['pagesteps'];
        $page = 1;
    }

    $query = mysql_query('SELECT *
                            FROM `group` 
                            WHERE group.id = "'.$_SESSION['gid'].'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $group = mysql_fetch_array($query);

    $query = mysql_query('SELECT device.*, bureau.name as bureauname,
                                 location.name as locationname
                            FROM `device`
                            LEFT JOIN `bureau` ON bureau.id=device.bid
                            LEFT JOIN `location` ON location.id=bureau.lid
                            WHERE device.gid="'.$_SESSION['gid'].'" LIMIT '.$start.','.$end) or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $devices = array();
    while($fetch=mysql_fetch_array($query))
        array_push($devices,$fetch);
        
    $sidebar = '
        <a href="#"><img src="'.$tmp_style_path.'icons/comment_edit.png"> edit group</a><br />
    ';
    
    write_header('details group <b>'.$group['name'].'</b>');

    echo '
        <table style="border: 1px solid #DDD; width: 100%;">
            <tr>
                <td style="text-align: right; vertical-align: top; padding-right: 10px; padding-bottom: 1.5em; width: 140px;">
                    <h2>'.$group['name'].'</h2>
                </td>
                <td class="col_right">
                    <b>'.$devicecount[0].' Ger&auml;te</b>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    '.$group['desc'].'
                </td>
            </tr>
            <tr>
                <td colspan="2" class="center padding">
                    '.siteGen($page, $devicecount[0]).'
                </td>
            </tr>
    ';
    foreach($devices as $d) {
        echo '
            <tr>
                <td class="col_left bold">
                    <a href="'.dire.'device/?id='.$d['id'].'">'.$d['type'].'</a>
                </td>
                <td class="normal">
                    <a href="'.dire.'bureau/?id='.$d['bid'].'">'.$d['locationname'].', '.$d['bureauname'].'</a>
                </td>
            </tr>
        ';
    }
    echo '
        <tr>
            <td colspan="2" class="center padding">
                '.siteGen($page, $devicecount[0]).'
            </td>
        </tr>
        </table><br />
    ';
    
    write_footer();

?>
