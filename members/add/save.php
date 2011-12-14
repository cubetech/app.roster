<?
    
    define('dire','../../');
    include(dire.'_env/exec.php');
    
    authed();
    session_start();
    
    $id = vGET('mid');
    
    if(!$id || !$id>0)
        error();
    
    $birthday[0] = vGET('birthday');
    $birthday[1] = explode('.', $birthday[0]);
    $_POST['birthday'] = $birthday[1][2].'-'.$birthday[1][1].'-'.$birthday[1][0];
    
    $query = "";
    
    while($data = current($_POST)) {
        $query .= '`'.key($_POST).'`="'.htmlentities($data).'", ';
        next($_POST);
    }
        
#    echo substr($query, 0, -2);
#    echo "<br /><br />";
#    echo $_SESSION['id'];
#    echo "<br><br>";
#    var_dump($_POST);
    
    mysql_query('UPDATE `members` SET
                                        '.$query.'
                                        `draft`="0"
                WHERE `id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
                
    $_SESSION['id'] = 0;
    
    write_header('member saved');
    
    echo '
    Der neue Eintrag wurde gespeichert.
    <br /><br />
    <table>
        <tr>
            <td class="col_left">
                <a href="'.dire.'members/"><input type="button" value="&laquo; zur&uuml;ck" /></a>
            </td>
            <td>
                <a href="'.dire.'members/details/?id='.$id.'"><input type="button" value="Member anzeigen &raquo;" /></a>
            </td>
        </tr>
    </table>
    ';
    
    write_footer();
        
?>