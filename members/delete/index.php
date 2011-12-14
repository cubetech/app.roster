<?

    define('dire', '../../');
    include(dire.'_env/exec.php');
    
    allowed();
    
    $id = vGET('id');
    
    if(!isset($id) || $id<1)
        error('transfer');
        
    $query = mysql_query('SELECT * FROM `members` WHERE `id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $member = mysql_fetch_array($query);
    
    write_header('delete member');
    
    echo 'M&ouml;chten Sie den Member
    <a href="'.dire.'members/details/?id='.$id.'" title="zum Profil"><b>'.$member['prename'].' '.$member['name'].'</b></a>
    wirklich l&ouml;schen?<br /><br />
    <i>Achtung: Diese Aktion kann nicht r&uuml;ckg&auml;ngig gemacht werden!</i><br /><br />
    <table>
        <tr>
            <td class="button_left">
                <a href="'.dire.'members/"><input type="button" value="&laquo; nein" /></a>
            </td>
            <td>
                <a href="'.dire.'members/delete/delete.php?id='.$id.'" title="Ja, Member wirklich l&ouml;schen"><input type="button" value="ja &raquo;" /></a>
            </td>
        </tr>
    </table>
    
    ';
    
    write_footer();
    
?>