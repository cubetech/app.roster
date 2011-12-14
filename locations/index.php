<?
    
    define('dire','../');
    include(dire.'_env/exec.php');

    allowed();
    session_start();

    $query = mysql_query('SELECT COUNT(*) FROM `location`') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $locationcount = mysql_fetch_array($query);
    
    $page = vGET('page');
    
    if($page>0) {
        if(ceil($locationcount[0]/$cfg['page']['pagesteps'])<$page)
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

    $query = mysql_query('SELECT * FROM `location` LIMIT '.$start.','.$end) or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $locations = array();
    while($fetch=mysql_fetch_array($query))
        array_push($locations,$fetch);
        
    $sidebar = '
        <a href="'.dire.'locations/export/?type=excel"><img src="'.$tmp_style_path.'icons/comment_edit.png"> export as xls (Excel)</a><br />
        <a href="'.dire.'locations/export/?type=csv"><img src="'.$tmp_style_path.'icons/comment_edit.png"> export as csv (OOo, iWork)</a><br />
    ';
    
    write_header('location list');

    echo '
        <table style="border: 1px solid #DDD; width: 100%;">
            <tr>
                <td style="text-align: right; vertical-align: top; padding-right: 10px; padding-bottom: 1.5em; width: 140px;">
                    <h2>Standort&uuml;bersicht</h2>
                </td>
                <td class="col_right">
                    <b>'.$locationcount[0].' Orte</b>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="center padding">
                    '.siteGen($page, $locationcount[0]).'
                </td>
            </tr>
    ';
    foreach($locations as $l) {
        
        $query = mysql_query('SELECT COUNT(*) FROM `bureau` WHERE `lid`="'.$l['id'].'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);

        $l['bureaucount'] = mysql_fetch_array($query);
        
        echo '
            <tr>
                <td class="col_left bold" style="width: 240px;">
                    <a href="'.dire.'location/?id='.$l['id'].'">'.$l['name'].'</a><br />
                    <small>'.$l['address'].', '.$l['zip'].' '.$l['location'].'</small>
                </td>
                <td class="normal" style="vertical-align: top;">
                    '.$l['bureaucount'][0].' R&auml;ume
                </td>
            </tr>
        ';
    }
    echo '
        <tr>
            <td colspan="2" class="center padding">
                '.siteGen($page, $locationcount[0]).'
            </td>
        </tr>
        </table><br />
    ';
    
    write_footer();

?>
