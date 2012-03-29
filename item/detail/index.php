<?php
	
	define('dire', '../../');
	include(dire . '_env/exec.php');
	
	$id = vGET('id');

	write_header('Artikeldetails ' . $id);
	
	linenav('Zur&uuml;ck', '../');
	
	?>
	
		<h1>Artikeldetails Nr. <?=$id?></h1>
		
	<?php
	
	write_footer();
	
?>