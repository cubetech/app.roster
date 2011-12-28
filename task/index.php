<?php

	define('dire', '../');
	include(dire . '_env/exec.php');
	
	$status = vGET('status');
	$direction = vGET('direction');
	$order = vGET('order');
	$order2 = vGET('order2');
	$outorder = '';
	
	if(!$direction || $direction == '' || $direction != 'DESC') {
		$direction = 'ASC';
		$redirection = 'DESC';
	} elseif($direction == 'DESC') {
		$redirection = 'ASC';
	}
	
	if(!$order || $order == '') {
		$order = 'duetime';
	} elseif(isset($order2) && $order2 != '') {
		$outorder = ', LOWER('.$order2.') '.$direction;
	} else {
		$outorder = '';
	}
	
	if(!$status || $status == '')
		$status = 1;
		
	$sidemsg = 'Erledigte Auftr&auml;ge';
	if($status==1)
		$sidemsg = 'Offene Auftr&auml;ge';
	elseif($status==3)
		$sidemsg = 'Gel&ouml;schte Auftr&auml;ge';
	
	$tasks = array();
	$query = mysql_query('SELECT * FROM `task` WHERE `status`="'.$status.'" ORDER BY LOWER('.$order.') '.$direction.$outorder) or sqlError(__FILE__,__LINE__,__FUNCTION__);
	while($fetch=mysql_fetch_array($query))
		array_push($tasks, $fetch);
		
	$tire = array();
	$query = mysql_query('SELECT * FROM `tire`') or sqlError(__FILE__,__LINE__,__FUNCTION__);
	while($fetch=mysql_fetch_array($query))
		$tire[$fetch[0]] = $fetch[1];
		
	write_header($sidemsg);
	
	?>

		<table width="850" border="0" cellspacing="0" cellpadding="10" class="table_main">
		  <tbody><tr style="background-color:#d9d8d8; font-size:14px;">
			<td width="40"><strong><a href="./?order=id&direction=<?=$redirection?>&status=<?=$status?>">NR <img src="<?=$tmp_style_path?>icons/sort.gif" /></a></strong></td>
			<td width="330"><strong><a href="./?order=company&order2=name&direction=<?=$redirection?>&status=<?=$status?>">KUNDE <img src="<?=$tmp_style_path?>icons/sort.gif" /></a></strong></strong></td>
			<td width="80"><strong><a href="./?order=tire&direction=<?=$redirection?>&status=<?=$status?>">TYP <img src="<?=$tmp_style_path?>icons/sort.gif" /></a></strong></strong></td>
			<td width="150"><strong><a href="./?order=duetime&direction=<?=$redirection?>&status=<?=$status?>">TERMIN <img src="<?=$tmp_style_path?>icons/sort.gif" /></a></strong></strong></td>
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
		  		$timeclass = '';
		  		if($t['duetime'] < time() && $status==1) {
		  			$timeclass = ' class="overtime"';
		  		}
		  			
		  		print '
						<tr class="'.$class.'">
							<td><a href="'.dire.'task/detail/?id='.$t['id'].'">'.$t['id'].'</a></td>
							<td><a href="'.dire.'task/detail/?id='.$t['id'].'">'.$t['company'].' '.$t['name'].'</a></td>
							<td>'.$tire[$t['tire']].'</td>
							<td'.$timeclass.'>'.date('d.m.Y H:i', $t['duetime']).'</td>
							<td><a href="'.dire.'task/edit/?id='.$t['id'].'">BEARBEITEN </a>| '.$statuschange.'</td>
						</tr>
		  		';
		  		
		  	}
		  
		  ?>

		</tbody></table>
		
	<?php
	
	write_footer();
	
?>