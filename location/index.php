<?
    
    define('dire','../');
    include(dire.'_env/exec.php');

    allowed();

    $id = vGET('id');
    if(!$id)
        error('transfer');

    $_SESSION['lid'] = $id;

    $query = mysql_query('SELECT * FROM `location` WHERE id = "'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $location = mysql_fetch_array($query);
    
    $query = mysql_query('SELECT COUNT(*) FROM `bureau` WHERE lid="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $bureaucount = mysql_fetch_array($query);

    $query = mysql_query('SELECT * FROM `bureau` WHERE lid = "'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $bureaus = array();
    while($fetch=mysql_fetch_array($query))
        array_push($bureaus,$fetch); 
 
    $sidebar = '
        <a href="#"><img src="'.$tmp_style_path.'icons/comment_edit.png"> edit location</a><br />
    ';
    
    write_header('details location <b>'.$location['name'].'</b>');

    echo '
        <table style="border: 1px solid #DDD; width: 100%;">
            <tr>
                <td style="text-align: right; vertical-align: top; padding-right: 10px; padding-bottom: 1.5em; width: 140px;">
                    <h2>'.$location['name'].'</h2>
                </td>
                <td class="col_right">
                    <b>'.$bureaucount[0].' R&auml;ume</b>
                </td>
            </tr>
            <tr>
                <td class="col_left">
                    Adresse:
                </td>
                <td class="bold">
                    '.$location['address'].'<br />
                    '.$location['zip'].' '.$location['location'].'<br />
                    <a href="http://maps.google.ch/maps?f=q&source=s_q&hl=de&geocode=&q='.str_replace(' ','+',$location['address']).',+'.$location['zip'].'+'.$location['location'].'" target="_blank">Google Maps</a>
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td class="col_left">
                    IP Range:
                </td>
                <td class="bold">
                    '.$location['ipstart'].' - '.$location['ipend'].'
                </td>
            </tr>
            <tr>
                <td class="col_left">
                    Alle R&auml;ume:
                </td>
                <td class="bold">
                ';
                    foreach($bureaus as $b) {
                        echo '<a href="'.dire.'bureau/?id='.$b['id'].'">'.$b['name'].'</a><br />';
                    }
                echo '
                </td>
            </tr>
         </table><br />
        ';
    
    write_footer();

?>
