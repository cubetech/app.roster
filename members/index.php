<?
    
    define('dire','../');
    include(dire.'_env/exec.php');

    allowed();
    session_start();

    $page = vGET('page');
    $pid = vGET('pid');
    $status = vGET('status');
    $search = vGET('search');
    $reset = vGET('reset');
    $addquery = '';
    $pid_text = '';
    $queryarr = array();
    $status_options = array('all', 'active', 'disabled', 'scrapped');
    if($reset) {
        $pid = 0;
        $status = 'all';
        $search = '';
    }
    $_SESSION['pid'] = $pid;
    $_SESSION['status'] = $status;
    $_SESSION['search'] = $search;
    
    $queryarr[] = 'draft != "1"';
    
//    if($status!='all')
 //       $queryarr[] = 'status.name="'.$status.'"';
    if ($pid>0)
        $queryarr[] = 'members.pid="'.$pid.'"';
    if ($search!='') {
        $search = htmlentities($search, ENT_QUOTES, 'UTF-8');
        $queryarr[] = 'UPPER(members.name) LIKE UPPER("'.str_replace("*", "%", $search).'") 
        OR UPPER(members.prename) LIKE UPPER("'.str_replace("*", "%", $search).'")
        OR UPPER(members.location) LIKE UPPER("'.str_replace("*", "%", $search).'")
        ';
    }
        
    for($i=0;$i<count($queryarr);$i++) {
        if($i==0)
            $addquery .= ' WHERE '.$queryarr[$i];
        else
            $addquery .= ' AND '.$queryarr[$i];
    }
    
    if($status!='all' || $pid>0 || $search!='') {
        $pid_text = '<small>Filter aktiv</small>';
    }

    $query = mysql_query('SELECT count(*)
                            FROM `members`
                            '.$addquery) or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $memberscount = mysql_fetch_array($query);
    
    if($page>0) {
        if(ceil($memberscount[0]/$cfg['page']['pagesteps'])<$page)
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
    
    $query = mysql_query('SELECT members.*
                            FROM `members`
                            '.$addquery.'
                            ORDER BY name ASC
                            LIMIT '.$start.','.$end.'') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $members = array();
    while($fetch=mysql_fetch_array($query))
        array_push($members,$fetch);
        
   $sidebar .= '
       <a href="'.dire.'members/add/" alt="add member" title="Member hinzuf&uuml;gen"><img src="'.$tmp_style_path.'icons/add.png"> <small>Member hinzuf&uuml;gen</small></a><br /><br />
       <a href="'.dire.'members/export/?type=excel" title="xls export - only for Microsoft Office"><img src="'.$tmp_style_path.'icons/comment_edit.png"> xls export</a><br />
        <a href="'.dire.'members/export/?type=csv" title="csv export - for Openoffice, iWork or others"><img src="'.$tmp_style_path.'icons/comment_edit.png"> csv export (OOo)</a><br />
        <br /><br />
        <fieldset><legend>Filter</legend>
            <form name="form" action="." method="POST">
            <table>
                <tr>
                    <td>Status:</td>
                    <td>
                        <select name="status">';
    $status_options = array('all', 'active', 'expire soon', 'expired', 'not assigned', 'drafts');
    foreach($status_options as $s) {
        $sel = '';
        if($s == $status)
            $sel = ' selected';
        $sidebar .= '<option'.$sel.'>'.$s.'</option>';
    }
    $sidebar .= '
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Ort:</td>
                    <td>
                        <select name="pid">
                            <option value="0">all</option>';
    $query = mysql_query('SELECT location.id, location.name FROM `location`');
    $places = array();
    while($fetch=mysql_fetch_array($query))
        array_push($places, $fetch);
        
    foreach($places as $p) {
        $sel = '';
        if($p['id'] == $pid)
            $sel = ' selected';
        $sidebar .= '<option value="'.$p['id'].'"'.$sel.'>'.$p['name'].'</option>';
    }
    
    $sidebar .= '
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Suche:</td>
                    <td><input type="text" name="search" value="'.$search.'"></td>
            </table>
            <br />
            <div style="text-align: right;"><a href="javascript:document.form.submit();"><img src="'.$tmp_style_path.'icons/true.png" style="width: 9px;"> Anwenden</a></div>
            <div style="text-align: right;"><a href="./?reset=true"><img src="'.$tmp_style_path.'icons/reload.png" style="width: 9px;"> Zur&uuml;cksetzen</a></div>
            </form>
        </fieldset>
    ';
    
    write_header('members list');

    echo '
        <table style="border: 1px solid #DDD; width: 100%;">
            <tr>
                <td colspan="3" style="text-align: right; vertical-align: top; padding-right: 10px; padding-bottom: 1.5em; width: 140px;">
                    <h2>Member&uuml;bersicht</h2>
                    '.$pid_text.'
                </td>
                <td colspan="2" class="col_right">
                    <b>'.$memberscount[0].' Members</b>
                </td>
            </tr>
            <tr>
                <td colspan="5">
                </td>
            </tr>
            <tr>
                <td colspan="5" class="center padding">
                    '.siteGen($page, $memberscount[0]).'
                </td>
            </tr>
    ';
    foreach($members as $d) {
        echo '
            <tr>
                <td class="bold">
                    <a href="'.dire.'members/details/?id='.$d['id'].'">'.$d['name'].' '.$d['prename'].'</a>
                </td>
                <td class="normal">
                    '.$d['location'].'
                </td>
                <td>
                    <a href="'.dire.'members/edit/?id='.$d['id'].'"><img src="'.$tmp_style_path.'icons/comment_edit.png" alt="edit" title="Member bearbeiten" style="width: 12px;" /></a>
                    <a href="'.dire.'members/delete/?id='.$d['id'].'"><img src="'.$tmp_style_path.'icons/delete.png" alt="delete" title="Member l&ouml;schen" style="width: 12px;" /></a>
                </td>
                ';
        $query = mysql_query('SELECT cards.*, cardtemplate.name as name
                                    FROM `cards`
                                    LEFT JOIN `cardtemplate` ON cardtemplate.id=cards.tid 
                                    WHERE `mid`="'.$d['id'].'"
                                    AND `expire`>"'.time().'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        $cards = array();
        while($fetch = mysql_fetch_array($query))
            array_push($cards, $fetch);
        if(count($cards)<1)
            echo '<td class="normal" colspan="2"><small><i>Keine Karte zugewiesen</i></small></td>';
        elseif(count($cards)==1) {
            echo '
                    <td class="normal">
                        <small><a href="'.dire.'members/cards/?id='.$cards[0]['id'].'">'.$cards[0]['name'].' Card</a></small>
                    </td>
                    <td class="normal">
                        <small>'.restTime($cards[0]['expire']-time()).'</small>
                    </td>
                </tr>
            ';
        } else 
            echo '<td class="normal" colspan="2">'.count($cards).' Karten zugewiesen</td>';
    }
    if(count($members)<1)
        echo '<tr><td colspan="5" class="center"><small><i>Keine Ergebnisse.<br>Passen Sie den Filter an um mehr Ergebnisse zu erhalten.</i></small></td></tr>';
    echo '
        <tr>
            <td colspan="5" class="center padding">
                '.siteGen($page, $memberscount[0]).'
            </td>
        </tr>
        </table><br />
    ';
    
    write_footer();

?>
