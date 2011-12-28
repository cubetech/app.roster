<?

    define('dire','../');
    include(dire.'_env/exec.php');
    
    $search = vGET('search');
    
    $tasks = array();
    $query = mysql_query('SELECT * FROM `task` 	WHERE	`status` = "1"
    											AND		`company` LIKE "%'.$search.'%" 
    											OR 		`name` LIKE "%'.$search.'%"
    											OR		`mobile` LIKE "%'.$search.'%"
    											OR		`allpneu_task` LIKE "%'.$search.'%"
    											OR		`comments` LIKE "%'.$search.'%"');
    while($fetch=mysql_fetch_array($query))
    	array_push($tasks, $fetch);
    	
    $newtasks = array();
    foreach($tasks as $t)
    	$newtasks[] = preg_replace('/('.$search.')/i', '<hl>$1</hl>', $t);
    
    $tasks = $newtasks;
        
	$tire = array();
	$query = mysql_query('SELECT * FROM `tire`') or sqlError(__FILE__,__LINE__,__FUNCTION__);
	while($fetch=mysql_fetch_array($query))
		$tire[$fetch[0]] = $fetch[1];
	
	write_header('Suche nach "'.$search.'"');
    
	?>
	
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
					
		  		print '
						<tr class="'.$class.'">
							<td><a href="'.dire.'task/detail/?id='.$t['id'].'&search='.$search.'">'.$t['id'].'</a></td>
							<td><a href="'.dire.'task/detail/?id='.$t['id'].'&search='.$search.'">'.$t['company'].' '.$t['name'].'</a></td>
							<td>'.$tire[$t['tire']].'</td>
							<td>'.date('d.m.Y H:i', $t['duetime']).'</td>
							<td><a href="#">BEARBEITEN </a>| <a href="#">ERLEDIGT </a></td>
						</tr>
		  		';
		  		
		  	}
		  
		  ?>

		</tbody></table>
		
	<?php
    
    write_footer();
    
?>