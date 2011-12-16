<?php

	define('dire', '../');
	include(dire . '_env/exec.php');
	
	$status = vGET('status');
	
	if(!$status || $status == '')
		$status = 1;
		
	$sidemsg = '<a href="./?status=1">OFFENE AUFTR&Auml;GE</a>';
	if($status==1)
		$sidemsg = '<a href="./?status=2">ABGESCHLOSSENE AUFTR&Auml;GE</a>';
	
	$tasks = array();
	$query = mysql_query('SELECT * FROM `task` WHERE `status`="'.$status.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
	while($fetch=mysql_fetch_array($query))
		array_push($tasks, $fetch);
		
	$tire = array();
	$query = mysql_query('SELECT * FROM `tire`') or sqlError(__FILE__,__LINE__,__FUNCTION__);
	while($fetch=mysql_fetch_array($query))
		$tire[$fetch[0]] = $fetch[1];
		
	write_header('Auftr&auml;ge', $sidemsg);
	
	?>
	<br />
		<table width="850" border="0" cellspacing="0" cellpadding="10" class="table_main">
		  <tbody><tr style="background-color:#d9d8d8; font-size:14px;">
			<td width="40"><strong>NR</strong></td>
			<td width="330"><strong>KUNDE</strong></td>
			<td width="80"><strong>TYP</strong></td>
			<td width="150"><strong>TERMIN</strong></td>
			<td width="200"><strong>AKTION</strong></td>
		  </tr>
		  
		  <?php 
		  
		  	for($i=0;$i<count($tasks);$i++) {
		  		$t = $tasks[$i];
		  		$class = '';
		  		if($i % 2 == 0)
		  			$class = 'gray';
		  			
		  		if(strlen($t['company'])>0)
		  			$t['company'] = $t['company'] . ' | ';
		  			
		  		if($t['status'] == 1) {
		  			$statuschange = '<a href="'.dire.'task/close/?id='.$t['id'].'&status=2">ERLEDIGT</a>';
		  		} else {
		  			$statuschange = '<a href="'.dire.'task/close/?id='.$t['id'].'&status=1">ER&Ouml;FFNEN</a>';
		  		} 
		  			
		  		print '
						<tr class="'.$class.'">
							<td><a href="'.dire.'task/detail/?id='.$t['id'].'">'.$t['id'].'</a></td>
							<td><a href="'.dire.'task/detail/?id='.$t['id'].'">'.$t['company'].' '.$t['name'].'</a></td>
							<td>'.$tire[$t['tire']].'</td>
							<td>'.date('d.m.Y H:i', $t['duetime']).'</td>
							<td><a href="'.dire.'task/edit/?id='.$t['id'].'">BEARBEITEN </a>| '.$statuschange.'</td>
						</tr>
		  		';
		  		
		  	}
		  
		  ?>

		</tbody></table>
		
	<?php
	
	write_footer();
	
?>