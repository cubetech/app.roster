<?
    
    define('dire','../../');
    include(dire.'_env/exec.php');

    allowed();
    session_start();

    $id = vGET('id');
    if(!$id && !isset($_SESSION['id']))
        error('transfer');
    elseif($id && !isset($_SESSION['id']) || $_SESSION['id']!=$id)
        $_SESSION['id'] = $id;

    $query = mysql_query('SELECT members.*, group.name as groupname
                            FROM `members` 
                            LEFT JOIN `group` ON group.id=members.gid
                            WHERE members.id = "'.$_SESSION['id'].'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $member = mysql_fetch_array($query);
    
    $query = mysql_query('SELECT cards.*, cardtemplate.name as name 
                            FROM `cards`
                            LEFT JOIN `cardtemplate` ON cardtemplate.id=cards.tid
                            WHERE `mid`="'.$member['id'].'"
                            AND `expire`>"'.time().'"
                            ORDER BY `tid` ASC') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $cards = array();
    while($fetch=mysql_fetch_array($query))
        array_push($cards, $fetch);

    $query = mysql_query('SELECT cards.*, cardtemplate.name as name 
                            FROM `cards`
                            LEFT JOIN `cardtemplate` ON cardtemplate.id=cards.tid
                            WHERE `mid`="'.$member['id'].'"
                            AND `expire`<"'.time().'"
                            ORDER BY `tid` ASC') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $cardsEnded = array();
    while($fetch=mysql_fetch_array($query))
        array_push($cardsEnded, $fetch);
    
    if($member['uid']!=authed())
        error('transfer');
        
    if($member['pid']>0) {
        $query = mysql_query('SELECT * FROM `files` WHERE `id`="'.$member['pid'].'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        $picture = mysql_fetch_array($query);
        $image[0] = dire.$cfg['page']['imgthumbpath'].$picture['name'];
        $image[1] = 1;
        $image[2] = $picture['id'];
    } else {
        $query = mysql_query('SELECT * FROM `files` WHERE `mid`="'.$member['id'].'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        $pictures = array();
        while($fetch=mysql_fetch_array($query))
            array_push($pictures, $fetch);
        if(count($pictures)>0) {
            $image[0] = dire.$cfg['page']['imgthumbpath'].$pictures[0]['name'];
            $image[1] = 1;
            $image[2] = $pictures[0]['id'];
        }
        else {
            $image[0] = $tmp_style_path.'icons/noimage.png';
            $image[1] = 0;
        }
    }
    
    $imagebar = '';
    
    if($image[1]==true) {
        $imagebar = '
            <a href="./download.php?id='.$image[2].'"><img src="'.$tmp_style_path.'icons/download.png" alt="download" title="Bild herunterladen" /></a><br />
            <a href="delete.php?id='.$id.'&fid='.$image[2].'"><img src="'.$tmp_style_path.'icons/delete.png" alt="delete" title="Bild l&ouml;schen" /></a>
        ';
    }

    $sidebar = '
        <a href="'.dire.'members/edit/?id='.$id.'"><img src="'.$tmp_style_path.'icons/comment_edit.png" alt="edit" title="Benutzer bearbeiten"> edit member</a><br />
        <a href="#"><img src="'.$tmp_style_path.'icons/add.png"> assign group</a><br />
        <a href="'.dire.'members/delete/?id='.$id.'"><img src="'.$tmp_style_path.'icons/delete.png" alt="delete" title="Benutzer l&ouml;schen"> delete user</a><br />
    ';
    
    list($y, $m, $d) = explode("-", $member['birthday']);
    $birthday = $d.'.'.$m.'.'.$y;
    
    write_header('member details <b>'.$member['prename'].' '.$member['name'].'</b>');

    echo '
        <table style="border: 1px solid #DDD; width: 100%;">
            <tr>
                <td colspan="2" style="text-align: right; vertical-align: top; padding-right: 10px; padding-bottom: 1.5em; padding-right: 5em;">
                    <h2>'.$member['prename'].' '.$member['name'].'</h2>
                    '.$member['location'].'
                </td>
                <td colspan="2" class="col_right">
                    <b>'.count($cards).' aktive Memberkarten</b>
                </td>
            </tr>
            <tr>
                <td class="col_left">
                    Vorname / Name:
                </td>
                <td class="bold">
                    '.$member['prename'].' '.$member['name'].'
                </td>
                <td rowspan="6" style="width: 120px; text-align: right;"><img src="'.$image[0].'" alt="'.$member['prename'].' '.$member['name'].'" style="width: 100px; border: 3px solid black;" /></td>
                <td rowspan="6" style="width: 22px; vertical-align: top; text-align: left;">
                    '.$imagebar.'
                </td>
            </tr>
            <tr>
                <td class="col_left">
                    Adresse:
                </td>
                <td class="bold">
                    '.$member['address'].'
                </td>
            </tr>
            <tr>
                <td class="col_left">
                    PLZ / Ort:
                </td>
                <td class="bold">
                    '.$member['zip'].' '.$member['location'].'
                </td>
            </tr>
            <tr>
                <td class="col_left">
                    Geburtsdatum:
                </td>
                <td class="bold">
                    '.$birthday.'
                </td>
            </tr>
            <tr>
                <td class="col_left">
                    Mailadresse:
                </td>
                <td class="bold">
                    ';
                    if($member['mail']!='')
                        echo '<a href="mailto:'.$member['mail'].'">'.$member['mail'].'</a>';
                    else
                        echo '<small><i>keine Mailadresse</i></small>';
                echo '
                </td>
            </tr>
            <tr>
                <td class="col_left">
                    Handynummer:
                </td>
                <td class="bold">
                    ';
                    if($member['handy']!='')
                        echo ''.$member['handy'].'';
                    else
                        echo '<small><i>keine Handynummer</i></small>';
                echo '
                </td>
            </tr>
            <tr>
                <td class="col_left">
                    Telefonnummer:
                </td>
                <td class="bold">
                    ';
                    if($member['phone']!='')
                        echo ''.$member['phone'].'';
                    else
                        echo '<small><i>keine Telefonnummer</i></small>';
                echo '
                </td>
            </tr>
            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
                <td class="col_left">
                    Gruppe:
                </td>
                <td class="bold">
                    '.$member['groupname'].'
                </td>
            </tr>
            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
                <td class="col_left">
                    Memberkarten:
                </td>
                <td colspan="3" class="bold bottom">
        ';
        if(count($cards)<1 && count($cardsEnded)<1)
            echo '<small><i>keine Memberkarten zugewiesen</i></small>';
        else {
            foreach($cards as $c) {
                echo '<a href="#">'.$c['name'].' Card - ID: '.sprintf('%06s', $c['id']).'</a> - <small>'.restTime($c['expire']-time()).'</small><br />';
            }
            foreach($cardsEnded as $c) {
                echo '<a href="#">'.$c['name'].' Card</a> - <small>abgelaufen am '.date('d.m.Y', $c['expire']).'</small><br />';
            }
        }
        echo '
                </td>
            </tr>
            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>';
        echo '
            </table><br />
        ';
    
    write_footer();

?>
