<?

    define('dire','../../');
    include(dire.'_env/exec.php');

    allowed();

    $uid = authed();
    $query = mysql_query('SELECT * FROM `users` WHERE `uid` = "'.$uid.'"');
    $member = mysql_fetch_array($query);

    $query = mysql_query('SELECT device.*, domain.host, manufacturer.name, status.color, status.name as statusname FROM `device` 
                            LEFT JOIN `domain` ON device.did=domain.id
                            LEFT JOIN `manufacturer` on manufacturer.id=device.mid
                            LEFT JOIN `status` on status.id=device.sid
                            WHERE device.uid = "'.$uid.'" ORDER BY device.stockid') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $devices = array();
    while($fetch=mysql_fetch_array($query))
        array_push($devices, $fetch);
    
    $sidebar = '
        <a href="'.dire.'device/add/"><img src="'.$tmp_style_path.'icons/add.png"> add device</a><br />
    ';

    write_header(translate('your devices'));
    
    if(count($devices) < 1)
        echo 'Es sind keine Ger&auml;te vorhanden.';
    
    foreach($devices as $d) {
        echo '
            <table style="border: 1px solid #DDD; width: 100%;">
                <tr>
                    <td style="text-align: right; vertical-align: bottom; padding-right: 10px; width: 140px;">
                        '.$d['name'].'
                        <h2><a href="./details/?id='.$d['id'].'">'.$d['type'].'</a></h2>
                    </td>
                    <td style="vertical-align: top; padding-right: 10px; width: 90px;">
                        '.$d['stockid'].'<br />
                    </td>
                    <td style="vertical-align: top;">
                        '.$d['hostname'].'.'.$d['host'].'<br />
                        <div style="margin-top: 5px; font-weight: bold; color: #'.$d['color'].';">'.$d['statusname'].'</div>
                    </td>
                </tr>
            </table>
            <br />
        ';
    }

    write_footer();

?>
