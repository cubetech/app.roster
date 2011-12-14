<?

    define('dire','');
    include(dire.'_env/exec.php');

    $query = mysql_query('SELECT cards.*, members.prename, members.name, cardtemplate.name as cardname,
                            location.name as locname
                            FROM `cards` 
                            LEFT JOIN `members` ON cards.mid=members.id
                            LEFT JOIN `cardtemplate` ON cards.tid=cardtemplate.id
                            LEFT JOIN `location` ON cards.lid=location.id
                            ORDER BY cards.expire ASC LIMIT 0,3') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $cards = array();
    while($fetch=mysql_fetch_array($query))
        array_push($cards, $fetch);

    write_header('Willkommen bei iMembr');

    ?>
        <br />
        <div style="border: 1px solid #EEE; vertical-align: bottom; padding-left: 49px;">
        <b>search members</b><br />
        <form action="search/" method="post">
            <input type="text" name="search" style="height: 32px; width: 300px; font-size: 26px;">
            <input type="image" src="<?=$tmp_style_path?>/icons/search.png" value="search" style="position: absolute; margin-top: 5px; padding-left: 20px;">
        </form>
        </div>
        <br />
        <i><small>expiring soon</small></i>
        <table style="border: 1px solid #EEEEEE; width: 100%;">
        <?
        foreach($cards as $c) {
        ?>
            <tr>
                <td class="left_head">
                    <small><b><?=$c['name']?> <?=$c['prename']?></b></small><br />
                    <h2><a href="<?=dire?>cards/details/?id=<?=$c['id']?>"><?=$c['cardname']?> Card</a></h2>
                </td>
                <td class="center_head">
                    <?=strftime("%d.%B %Y", $c['expire']) ?><br />
                    <i><small>abgelaufen</small></i>
                </td>
                <td class="col_right bold">
                    <small><a href="<?=dire?>location/?id=<?=$c['id']?>"><?=$c['locname']?></a></small>
                </td>
            </tr>
        <?
        }
        ?>
        </table>
        <br /><br />
        Willkommen bei iMembr
        <br /><br />
        Wir w&uuml;nschen Ihnen mit der Memberverwaltung iMembr viel Vergn&uuml;gen! Ihre Anregungen und
        Ihre Kritik sind uns wichtig, z&ouml;gern Sie nicht, mit uns Kontakt aufzunehmen!
    <?

    write_footer();

?>
