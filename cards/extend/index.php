<?

    define('dire','../../');
    include(dire.'_env/exec.php');
    
    $id = vGET('id');
    
    if(!$id)
        error('transfer');
    
    $query = mysql_query('SELECT cards.*, cardtemplate.name as name, cardtemplate.cost as price, cardtemplate.duration as time,
                            members.name as surname, members.prename as prename,
                            location.name as locationname, location.zip as zip, location.location as location
                            FROM `cards` 
                            LEFT JOIN `cardtemplate` ON cardtemplate.id=cards.tid
                            LEFT JOIN `members` ON members.id=cards.mid
                            LEFT JOIN `location` ON location.id=cards.lid
                            WHERE cards.id="'.$id.'"
                            ') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $card = mysql_fetch_array($query);
    
    if(!$card)
        error('transfer');
    
    $query = mysql_query('SELECT name, prename, id FROM `members` WHERE `draft`!="1" ORDER BY `name` ASC') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $members = array();
    while($fetch=mysql_fetch_array($query))
        array_push($members, $fetch);

    write_header('extend card '.$id);
    
    echo '
        <script type= "text/javascript">/*<![CDATA[*/
            $(function() {
        		$(".datepicker").datepicker($.datepicker.regional[\'de-CH\']);
        	});
        </script>
        <form action="save.php" method="POST"> 
        <input type="hidden" name="id" value="'.$id.'" />
        <table style="border: 1px solid #DDD; width: 100%;">
            <tr>
                <td style="text-align: right; vertical-align: top; padding-bottom: 1.5em; padding-right: 1em; width: 200px;">
                    <h2>Karte verl&auml;ngern</h2>
                    '.$card['name'].' Card
                </td>
                <td class="col_right">
                    G&uuml;ltig:<b> '.restTime($card['expire']-time()).'</b>
                </td>
            </tr>
            <tr>
                <td class="col_left">Member:</td>
                <td class="bold">
                    <select name="member">';
                foreach($members as $m){
                    $selected = '';
                    if($m['id']==$card['mid'])
                        $selected = ' selected';
                    echo '<option value="'.$m['id'].'"'.$selected.'>'.$m['name'].' '.$m['prename'].'</option>
                    ';
                }
                echo '</td>
            </tr>
            <tr>
                <td class="col_left">Verl&auml;ngerung:</td>
                <td class="bold">'.$card['time'].' Monate</td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="col_left">Preis:</td>
                <td class="bold"><input type="text" name="price" value="'.sprintf("%.2f",$card['price']).'"></td>
            </tr>
            <tr>
                <td class="col_left">Bereits bezahlt:</td>
                <td class="bold"><input type="text" name="payed"></td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="col_left">G&uuml;ltig ab:</td>
                <td class="bold"><input type="text" name="date" class="datepicker" value="'.date('d.m.Y').'"></td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="col_left">Rechnung ausdrucken:</td>
                <td class="bold"><input type="checkbox" name="invoice"></td>
            </tr>
            <tr>
                <td class="col_left">Grafik erstellen:</td>
                <td class="bold"><input type="checkbox" name="image" checked></td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="col_left">
                    <a href="'.dire.'cards/details/?id='.$id.'"><input type="button" value="&laquo; abbrechen" /></a>
                </td>
                <td>
                    <input type="submit" value="speichern &raquo;" />
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
        </table>
        </form>
    ';

    write_footer();

?>