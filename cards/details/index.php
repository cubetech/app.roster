<?

    define('dire', '../../');
    include(dire.'_env/exec.php');
    
    $id = vGET('id');
    
    if(!$id)
        error('transfer');
    
    $query = mysql_query('SELECT cards.*, cardtemplate.name as name,
                            members.name as surname, members.prename as prename,
                            location.name as locationname, location.zip as zip, location.location as location
                            FROM `cards` 
                            LEFT JOIN `cardtemplate` ON cardtemplate.id=cards.tid
                            LEFT JOIN `members` ON members.id=cards.mid
                            LEFT JOIN `location` ON location.id=cards.lid
                            WHERE cards.id="'.$id.'"
                            ') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $card = mysql_fetch_array($query);
    
    $sidebar .= '
        <a href="'.dire.'cards/extend/?id='.$id.'" title="Karte verl&auml;ngern"><img src="'.$tmp_style_path.'icons/true.png" alt="extend card"> Karte verl&auml;ngern</a><br />
        <a href="'.dire.'cards/retract/?id='.$id.'" title="Karte zur&uuml;ckziehen"><img src="'.$tmp_style_path.'icons/delete.png"  alt="retract card"> Karte sperren</a><br />
        <br />
    ';    
        
    write_header('details card '.$id);
    
    echo '
        <table style="border: 1px solid #DDD; width: 100%;">
            <tr>
                <td style="text-align: right; vertical-align: top; padding-bottom: 1.5em; padding-right: 1em; width: 200px;">
                    <h2>'.$card['name'].' Card</h2>
                    ausgestellt: '.date('d.m.Y', $card['issued']).'
                </td>
                <td class="col_right">
                    G&uuml;ltig:<b> '.restTime($card['expire']-time()).'</b>
                </td>
            </tr>
            <tr>
                <td class="col_left">G&uuml;ltig bis:</td>
                <td class="bold">'.date('d.m.Y', $card['expire']).'</td>
            </tr>
            <tr>
                <td class="col_left">Ausgestellt am:</td>
                <td class="bold">'.date('d.m.Y', $card['issued']).'</td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="col_left">Zugewiesen an:</td>
                <td class="bold"><a href="'.dire.'members/details/?id='.$card['mid'].'" title="Member ansehen">'.$card['surname'].' '.$card['prename'].'</a></td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="col_left">Location</td>
                <td class="bold"><a href="'.dire.'location/?id='.$card['lid'].'" title="Location anzeigen">'.$card['locationname'].', '.$card['zip'].' '.$card['location'].'</a></td>
            </tr>
        </table>
    ';
    
    write_footer();
    
?>