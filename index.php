<?

    define('dire','');
    include(dire.'_env/exec.php');

    $query = mysql_query('SELECT * FROM task') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $tasks = array();
    while($fetch=mysql_fetch_array($query))
        array_push($tasks, $fetch);

    write_header('Dashboard');

    ?>

		Es sind aktuell <?=count($tasks)?> Auftr&auml;ge offen.

    <?

    write_footer();

?>
