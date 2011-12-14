<?

    define('dire','../');
    include(dire.'_env/exec.php');
    
    $search = vGET('search');
    
    $query = mysql_query('SELECT * FROM `members` WHERE MATCH(prename, name, address, location, mail, handy, phone, memberid) AGAINST ("'.$search.'")');
    $result = array();
    while($fetch=mysql_fetch_array($query))
        array_push($result, $fetch);
    
    write_header('search');
    
    echo '<table style="border: 1px solid #DDD; width: 100%;">';
    if(count($result)<1)
        echo '<tr><td><i>Keine Suchergebnisse f&uuml;r den Begriff "<b>'.$search.'</b>" gefunden.</i></td></tr>';
    else {
        echo '<tr><td colspan="5" class="center"><i>'.count($result).' Suchergebnisse f&uuml;r den Begriff "<b>'.$search.'</b>" gefunden:<br /><br /></td></tr>';
        foreach($result as $d) {
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
    }
    echo '</table>';
    
    //var_dump($result);
    
    write_footer();
    
?>