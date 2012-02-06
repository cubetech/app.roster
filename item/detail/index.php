<?php
	
	define('dire', '../../');
	include(dire . '_env/exec.php');
	
	$id = vGET('id');

	write_header('Artikeldetails ' . $id);
	
	?>
	
		<p class="pull-left">
			<a class="btn" href="<?=dire?>item/">
			    <i class="icon-chevron-left"></i> Zur&uuml;ck
			</a>
		</p>
		
		<br /><br /><br />
		
		<h1>Artikeldetails Nr. <?=$id?></h1>
		
	<?php
	
	write_footer();
	
?>