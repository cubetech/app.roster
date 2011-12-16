<?

    define('dire','');
    include(dire.'_env/exec.php');

    $query = mysql_query('SELECT COUNT(*) FROM task WHERE `status`="1"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
	$task = mysql_fetch_array($query);
	
	$query = mysql_query('SELECT COUNT(*) FROM `task` WHERE `status`="2"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
	$closed = mysql_fetch_array($query);
	
    write_header('Dashboard');

    ?>

		<p>Es sind aktuell <strong><?=$task[0]?> Auftr&auml;ge</strong> offen.</p>
		<p>Bereits <?=$closed[0]?> Auftr&auml;ge wurden erledigt.</p>

    <?

    write_footer();

?>
