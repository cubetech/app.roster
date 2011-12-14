<?
    
    define('dire','../../../');
    include(dire.'_env/exec.php');

    allowed();

    $id = vGET('id');
    if(!$id)
        error('transfer');

    $_SESSION['id'] = $id;

    $query = mysql_query('SELECT device.*, domain.host, manufacturer.name, 
                                 group.name as groupname, users.prename, 
                                 users.name as surname, reseller.name as resellername, 
                                 location.name as locationname, bureau.name as bureauname
                            FROM `device` 
                            LEFT JOIN `domain` ON device.did=domain.id
                            LEFT JOIN `manufacturer` on manufacturer.id=device.mid
                            LEFT JOIN `group` on group.id=device.gid
                            LEFT JOIN `users` on users.uid=device.uid
                            LEFT JOIN `reseller` on reseller.id=device.rid
                            LEFT JOIN `bureau` on bureau.id=device.bid
                            LEFT JOIN `location` on location.id=bureau.lid
                            WHERE device.id = "'.$id.'" ORDER BY device.stockid') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $device = mysql_fetch_array($query);

    if($device['uid']!=authed())
        error('transfer');

    $sidebar = '
        <a href="#"><img src="'.$tmp_style_path.'icons/comment_edit.png"> edit device</a><br />
        <a href="#"><img src="'.$tmp_style_path.'icons/add.png"> assign group</a><br />
    ';
    
    write_header('details device <b>'.$device['type'].'</b>');

    echo '
        <table style="border: 1px solid #DDD; width: 100%;">
            <tr>
                <td style="text-align: right; vertical-align: top; padding-right: 10px; padding-bottom: 1.5em; width: 140px;">
                    '.$device['name'].'
                    <h2>'.$device['type'].'</h2>
                </td>
                <td class="col_right bold">
                    '.$device['stockid'].'
                </td>
            </tr>
            <tr>
                <td class="col_left">
                    Seriennummer:
                </td>
                <td class="bold">
                    '.$device['serial'].'
                </td>
            </tr>
            <tr>
                <td class="col_left">
                    Kaufdatum:
                </td>
                <td class="bold">
                    '.date("d.m.Y", $device['buytime']).'
                </td>
            </tr>
            <tr>
                <td class="col_left">
                    Garantie bis:
                </td>
                <td class="bold">
                    '.date("d.m.Y", $device['warrantytime']).'
                </td>
            </tr>
            <tr>
                <td class="col_left">
                    Kaufpreis:
                </td>
                <td class="bold">
                    CHF '.number_format($device['price'], 2, ".", "").'
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>';
            
            if($device['gid']>0) {
                echo '
                    <tr>
                        <td class="col_left">
                            Gruppe:
                        </td>
                        <td class="bold">
                            <a href="'.dire.'group/?id='.$device['gid'].'">'.$device['groupname'].'</a>
                        </td>
                    </tr>
                ';
            }
            if($device['uid']>0) {
                echo '
                    <tr>
                        <td class="col_left">
                            Benutzer:
                        </td>
                        <td class="bold">
                            <a href="'.dire.'profile/?uid='.$device['uid'].'">'.$device['prename'].' '.$device['surname'].'</a>
                        </td>
                    </tr>
                ';
            }
            if($device['bid']>0) {
                echo '
                    <tr>
                        <td class="col_left">
                            Einsatzort:
                        </td>
                        <td class="bold">
                            <a href="'.dire.'bureau/?id='.$device['bid'].'">'.$device['locationname'].', '.$device['bureauname'].'</a>
                        </td>
                    </tr>
                ';
            }
            if($device['rid']>0) {
                echo '
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="col_left">
                            Reseller:
                        </td>
                        <td class="bold">
                            <a href="'.dire.'reseller/?id='.$device['rid'].'">'.$device['resellername'].'</a>
                        </td>
                    </tr>
                ';
            }
        echo '
            </table><br />
        ';
    
    write_footer();

?>
