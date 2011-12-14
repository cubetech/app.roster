<?
    
    define('dire','../../');
    include(dire.'_env/exec.php');

    allowed();
    session_start();
    
    $update = vGET('update');
    $referer = parse_url($_SERVER['SCRIPT_NAME']);
    $referer = explode('/', $referer['path']);
    $reffile = $referer[count($referer)-1];
    $device = array();
        
    if($update=='true')
        $_SESSION['modtime'] = time();
                    
    if(isset($_SESSION['modtime']) && $_SESSION['modtime']<(time()-600) && isset($_SESSION['mid'])) {
        quest('In Ihrer Session sind noch ungespeicherte Daten vorhanden, welche jedoch schon mehr als 10 Minuten nicht mehr bearbeitet wurden. Sollen die Daten geladen werden oder mit einer neuen Vorlage begonnen werden?', $reffile . '?update=true', 'Ja, Daten laden', './unset.php?unset=true', 'Nein, neue Vorlage');
    }
    
    if(isset($_SESSION['mid'])) {
        $query = mysql_query('SELECT * FROM `members` WHERE `id`="'.$_SESSION['mid'].'" AND `draft`="1"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        $checkmysql = mysql_fetch_array($query);
    }
        
    if(isset($_SESSION['modtime']) && $_SESSION['modtime']>(time()-600) && $_SESSION['mid']>0 && isset($checkmysql['id']) && $checkmysql['id']>0) {
        $query = mysql_query('SELECT * FROM `members` WHERE `id`="'.$_SESSION['mid'].'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        $member = mysql_fetch_array($query);
    } else {
        mysql_query('INSERT INTO `members` (lastmodified) VALUES ("'.time().'")') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        $_SESSION['mid'] = mysql_insert_id();
        $_SESSION['modtime'] = time();
    }

    $groups = array();
    $query = mysql_query('SELECT * FROM `group`') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    while($fetch=mysql_fetch_array($query))
        array_push($groups, $fetch);
    
    $sidebar = '
    ';
    
    $query = mysql_query('SELECT COUNT(*) FROM `members`') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $membercount = mysql_fetch_array($query);
    $count = $membercount[0];
    
    $query = mysql_query('SELECT * FROM `members` WHERE `memberid` LIKE "%'.$count.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $check = mysql_fetch_array($query);
    $memberid = $count;
    
    while($check['id']>0) {
        $count++;
        $memberid = $count;
        $query = mysql_query('SELECT * FROM `members` WHERE `memberid` LIKE "%'.$count.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        $check = mysql_fetch_array($query);
    }
    
    $images = array();
    $query = mysql_query('SELECT * FROM `files` WHERE `mid`="'.$_SESSION['mid'].'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    while($fetch=mysql_fetch_array($query))
        array_push($images, $fetch);
    
    $country = array();
    $query = mysql_query('SELECT * FROM `country`') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    while($fetch=mysql_fetch_array($query))
        array_push($country, $fetch);
    
    write_header('add member');

    echo '
        <script type= "text/javascript">/*<![CDATA[*/
            $(function() {
        		$(".datepicker").datepicker($.datepicker.regional[\'de-CH\']);
        	});
            $(document).ready(function(){
                new AjaxUpload(\'#documents\', {
            		action: \'document.php\',
            		name: \'document\',
            		onComplete : function(file, response){
            		    var data = new String(response);
            		    var result = data.search(/ERROR.+/);
            		    if (result != -1) {
            		        alert(response);
            		    } else {
                		    var responseData = response.split(",");
                		    var delButton = \'&nbsp;<a href="#" class="itemDelete"><img src="'.$tmp_style_path.'icons/delete_small.gif" alt="delete" /></a>\';
                			$(\'<li class="files" id="\' + responseData[0] + \'"></li>\').appendTo($(\'#upload #files\')).html((file.length>21) ? file.substring(0, 14) + \'...\' + file.substring(file.length-6, file.length) + \', \' + responseData[1] + delButton : file + \', \' + responseData[1] + delButton);
        			    }
            		}
            	});	
                $(\'.itemDelete\').live(\'click\', function() {
                    var parent = $(this).parent();
                    $.ajax({
            			type: \'get\',
            			url: \'delete.php\',
            			data: \'fid=\' + parent.attr(\'id\'),
            			beforeSend: function() {
            				parent.animate({\'backgroundColor\':\'#fb6c6c\'},300);
            			},
            			success: function() {
            				parent.slideUp(300,function() {
            					parent.remove();
            				});
            			}
            		});
                    return false;
                });	
                $(\'._autosave\').live(\'change\', function(){
                    var id = $(this).attr(\'name\');
                    var val = htmlentities($(this).val());
                    $.ajax({
            			type: \'post\',
            			url: \'ajaxsave.php\',
            			data: \'save=\' + id + \':::\' + val.replace(/\+/g, "%2B"),
            		});                    
                    return false;
                });
            });/*]]>*/
        </script>        
        
        <table style="border: 1px solid #DDD; width: 100%;">
        <tr>
        <td>
        <form method="POST" action="save.php" name="form">
        <table style="width:100%">
            <tr>
                <td style="text-align: left; vertical-align: top; padding-right: 50px; padding-bottom: 1.5em;" colspan="2">
                    <h2>Neuer Member erfassen</h2>
                </td>
            </tr>
            <tr>
                <td class="col_left">
                    Gruppe:
                </td>
                <td class="bold">
                <select name="gid" class="_autosave">
                    <option value="0"></option>
                ';
                foreach($groups as $g) {
                    $selected = '';
                    if($member['gid'] > 0 && $member['gid'] == $g['id'])
                        $selected = ' selected';
                    echo '<option value="'.$g['id'].'"'.$selected.'>'.$g['name'].'</option>
                    ';
                }
                echo '
                </select>
                </td>
            </tr>
            <tr><td colspan="2">&nbsp;</td></tr>
            <tr>
                <td class="col_left">
                    Name:
                </td>
                <td class="bold">
                    <input type="text" name="name" class="_autosave" value="'.$member['name'].'">
                </td>
            </tr>
            <tr>
                <td class="col_left">
                    Vorname:
                </td>
                <td class="bold">
                    <input type="text" name="prename" class="_autosave" value="'.$member['prename'].'">
                </td>
            </tr>
            <tr>
                <td class="col_left">
                    Adresse:
                </td>
                <td class="bold">
                    <input type="text" name="address" class="_autosave" value="'.$member['address'].'">
                </td>
            </tr>
            <tr>
                <td class="col_left">
                    PLZ / Ort:
                </td>
                <td class="bold">
                    <input type="text" name="zip" class="_autosave plz" maxlength="5" value="'.$member['zip'].'">
                    <input type="text" name="location" class="_autosave" value="'.$member['location'].'">
                </td>
            </tr>
            <tr>
                <td class="col_left">
                    Land:
                </td>
                <td class="bold">
                    <select name="cid" class="_autosave">
                        <option value="0"></option>
                    ';
                    foreach($country as $c) {
                        $selected = '';
                        if($member['cid'] > 0 && $member['cid'] == $c['id'])
                            $selected = ' selected';
                        echo '<option value="'.$c['id'].'"'.$selected.'>'.$c['name'].'</option>
                        ';
                    }
                    echo '
                    </select>
                </td>
            </tr>
            <tr><td colspan="2">&nbsp;</td></tr>
            <tr>
                <td class="col_left">
                    Geburtstag:
                </td>
                <td class="bold">
                    <input type="text" name="birthday" class="_autosave datepicker" value="'.date("d.m.Y", $member['birthday']).'">
                </td>
            </tr>
            <tr>
                <td class="col_left">
                    Mailadresse:
                </td>
                <td class="bold">
                    <input type="text" name="mail" class="_autosave" value="'.$member['mail'].'">
                </td>
            </tr>
            <tr>
                <td class="col_left">
                    Handynummer:
                </td>
                <td class="bold">
                    <input type="text" name="handy" class="_autosave" value="'.$member['handy'].'">
                    <br /><small>Format: +41790000000</small>
                </td>
            </tr>
            <tr>
                <td class="col_left">
                    Telefonnummer:
                </td>
                <td class="bold">
                    <input type="text" name="phone" class="_autosave" value="'.$member['phone'].'">
                    <br /><small>Format: +41310000000</small>
                </td>
            </tr>
            <tr><td colspan="2">&nbsp;</td></tr>
            <tr>
                <td class="col_left">
                    Membernummer:
                </td>
                <td class="bold _autosave" name="memberid">
                    <input type="hidden" name="memberid" value="'.sprintf('%06s', $memberid).'">
                    '.sprintf('%06s', $memberid).'
                </td>
            </tr>
            <tr><td colspan="2">&nbsp;</td></tr>
            <tr>
                <td class="col_left">
                    Bilder:
                </td>
                <td class="bold" id="upload">
                    <input id="documents" type="file" size="20" /><br />
                    <small>Es sind nur Dateien in Bildformaten erlaubt (jpg, gif, png, bmp, tiff)</small><br />
                    <div class="files">
                        <ul class="files" id="files">
            ';
            if(count($images)>0) {
                foreach($images as $i) {
                    $delButton = '&nbsp;<a href="#" class="itemDelete"><img src="'.$tmp_style_path.'icons/delete_small.gif" alt="delete" /></a>';
                    echo '<li class="files" id="'.$i['id'].'">';
                    if(strlen($i['originalname'])>21)
                        echo substr($i['originalname'], 0, 14).'...'.substr($i['originalname'], (strlen($i['originalname'])-6), strlen($i['originalname']));
                    else
                        echo $i['originalname'].', '.getSize($i['size']);
                    echo $delButton . '</li>';
                }
            }
            echo '
                        </ul>
                    </div>
                </td>
            </tr>
            <tr><td colspan="2">&nbsp;</td></tr>
            <tr>
                <td class="col_left">
                    <a href="./"><input type="button" value="&laquo; abbrechen" /></a>
                </td>
                <td>
                    <input type="submit" value="speichern &raquo;" />
                </td>
            </tr>

            ';
        echo '
            </table>
            </form>
            </td>
            </tr>
            </table>
            <br />
        ';
    
    write_footer();

?>
