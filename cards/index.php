<?

    define('dire', '../');
    include(dire.'_env/exec.php');
    
    allowed();
    
    $page = vGET('page');
    $sort = vGET('sort');
    $sortdir = vGET('sortdir');
    
    $sortArray = array('', 'name', 'surname', 'issued', 'expire');
    
    if(!$sort || $sort>count($sortArray) || !is_numeric($sort))
        $sort = '1';
    if(!$sortdir || $sortdir=="DESC")
        $sortdir = 'ASC';
    else
        $sortdir = 'DESC';
    
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
    
    $query = mysql_query('SELECT cards.*, cardtemplate.name as name,
                            members.name as surname, members.prename as prename
                            FROM `cards`
                            LEFT JOIN `cardtemplate` ON cardtemplate.id=cards.tid
                            LEFT JOIN `members` ON members.id=cards.mid
                            ORDER BY `'.$sortArray[$sort].'` '.$sortdir.'
                            LIMIT '.$start.','.$end.'
                            ') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $cards = array();
    while($fetch=mysql_fetch_array($query))
        array_push($cards, $fetch);
        
    $pid_text = '';
    
    $sidebar .= '
        <a href="'.dire.'members/add/" alt="add card" title="Karte hinzuf&uuml;gen"><img src="'.$tmp_style_path.'icons/add.png"> Karte hinzuf&uuml;gen</a><br /><br />
    ';
        
    write_header('card list');
    
    echo '
        <table style="border: 1px solid #DDD; width: 100%;">
            <tr>
                <td colspan="2" style="text-align: right; vertical-align: top; padding-right: 10px; padding-bottom: 1.5em;">
                    <h2>Karten&uuml;bersicht</h2>
                    '.$pid_text.'
                </td>
                <td colspan="2" class="col_right">
                    <b>'.count($cards).' Karten</b>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                </td>
            </tr>
            <tr>
                <td colspan="4" class="center padding">
                    '.siteGen($page, count($cards)).'
                </td>
            </tr>
            <tr>
                <td class="small nobr">Name <a href="./?sort=1&sortdir='.$sortdir.'" title="Sortieren nach Name"><img src="'.$tmp_style_path.'icons/sort.png" alt="sort" /></a></td>
                <td class="small nobr">Zugewiesener Member <a href="./?sort=2&sortdir='.$sortdir.'" title="Sortieren nach Member"><img src="'.$tmp_style_path.'icons/sort.png" alt="sort" /></a></td>
                <td class="small nobr">Ausgestellt am <a href="./?sort=3&sortdir='.$sortdir.'" title="Sortieren nach Aussstelldatum"><img src="'.$tmp_style_path.'icons/sort.png" alt="sort" /></a></td>
                <td class="small nobr">Restdauer <a href="./?sort=4&sortdir='.$sortdir.'" title="Sortieren nach Restdauer"><img src="'.$tmp_style_path.'icons/sort.png" alt="sort" /></a></td>
            </tr>
    ';
    foreach($cards as $c) {
        echo '
            <tr>
                <td class="bold">
                    <a href="'.dire.'cards/details/?id='.$c['id'].'">'.$c['name'].' Card</a>
                </td>
                <td class="normal">
                    <small><a href="'.dire.'members/details/?id='.$c['mid'].'" title="Memberprofil anzeigen">'.$c['surname'].' '.$c['prename'].'</a></small>
                </td>
                <td class="normal">
                    '.date('d.m.Y', $c['issued']).'
                </td>
                <td class="normal">
                    '.restTime($c['expire']-time(),'').'
                </td>
            </tr>';
    }
    if(count($cards)<1)
        echo '<tr><td colspan="4" class="center"><small><i>Keine Ergebnisse.<br>Passen Sie den Filter an um mehr Ergebnisse zu erhalten.</i></small></td></tr>';
    echo '
        <tr>
            <td colspan="4" class="center padding">
                '.siteGen($page, count($cards)).'
            </td>
        </tr>
        </table><br />
    ';
    
    
    write_footer();

?>