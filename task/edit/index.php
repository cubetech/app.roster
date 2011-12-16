<?php

	define('dire', '../../');
	include(dire . '_env/exec.php');
	
	$id = vGET('id');
	
	$query = mysql_query('SELECT * FROM `task` WHERE `id`="'.$id.'"');
	$task = mysql_fetch_array($query);
	
	$location = array();
	$query = mysql_query('SELECT * FROM `location`') or sql_error(__FILE__,__LINE__,__FUNCTION__);
	while($fetch=mysql_fetch_array($query))
		array_push($location, $fetch);
	
	$tire = array();
	$query = mysql_query('SELECT * FROM `tire`') or sql_error(__FILE__,__LINE__,__FUNCTION__);
	while($fetch=mysql_fetch_array($query))
		array_push($tire, $fetch);
	
	$tasklist = array();
	$query = mysql_query('SELECT * FROM `tasklist`') or sql_error(__FILE__,__LINE__,__FUNCTION__);
	while($fetch=mysql_fetch_array($query))
		array_push($tasklist, $fetch);

	$reserve = array();
	$query = mysql_query('SELECT * FROM `reserve`') or sql_error(__FILE__,__LINE__,__FUNCTION__);
	while($fetch=mysql_fetch_array($query))
		array_push($reserve, $fetch);

	$users = array();
	$query = mysql_query('SELECT * FROM `users` WHERE `infouser` = "true"') or sql_error(__FILE__,__LINE__,__FUNCTION__);
	while($fetch=mysql_fetch_array($query))
		array_push($users, $fetch);

	write_header('Auftrag Nr. '.$id.' bearbeiten');
	
	$_SESSION['complete'] = true;
	
	?>
	
		<form id="form" method="post" action="<?=dire?>task/save/">
			
			<div class="formpoint">
		
			<h3>Kunde</h3>
		
			<table id="form" width="100%" style="border: 0;">
			
				<tr>
					<td>Firma</td>
					<td><input type="text" class="box3" name="company" id="company" value="<?=$task['company']?>"/></td>
					<td>Name</td>
					<td><input type="text" class="box3" name="name" id="name" data-validate="validate(required, minlength(3))"  value="<?=$task['name']?>"/></td>
				</tr>
				<tr>
					<td>Natel</td>
					<td><input type="text" class="box3" name="mobile" id="mobile" data-validate="validate(required, digits)" value="<?=$task['mobile']?>"></td>
					<td>Auftragsnr.<br>AllPneu</td>
					<td><input type="text" class="box3" name="allpneu_task" value="<?=$task['allpneu_task']?>"></td>
				</tr>
				
			</table>
			
			</div>
			
			<h3>Pneu / Rad</h3>
			
			<table id="form" width="100%" style="border: 0;">
			
				<tr>
					<td style="width: 160px;">Typ</td>
					<td>
						<select name="tire" class="box3" name="type">
							<?php 									
								foreach($tire as $t) {
									$selected = '';
									if($t['id'] == $task['tire'])
										$selected = ' selected';
									print '<option value="'.$t['id'].'"'.$selected.'>'.$t['name'].'</option>';
								}
							?>
						</select>									
					</td>
				</tr>
				<tr>
					<td style="vertical-align: top;">Standort</td>
					<td>
						<?php 
							$loc = explode(',', $task['location']);
							$selected = array();
							foreach($loc as $l) {
								$selected[$l] = ' checked';
							}
							for($i=0; $i<count($location); $i++) {
								$l = $location[$i];
								$validate = '';
								if($i===0)
									$validate = ' data-validate="validate(minselect(1))"';
								print '<input type="checkbox" name="location[]" value="'.$l['id'].'"'.$validate.@$selected[$l['id']].'> '.$l['name'].'<br />';
							}
						?>
					</td>
				</tr>
				<tr>
					<td style="vertical-align: top;">Auftrag</td>
					<td>
						<?php 
							$tk = explode(',', $task['task']);
							$selected = array();
							foreach($tk as $t) {
								$selected[$t] = ' checked';
							}
							for($i=0; $i<count($tasklist); $i++) {
								$t = $tasklist[$i];
								$validate = '';
								if($i===0)
									$validate = ' data-validate="validate(minselect(1))"';
								print '<input type="checkbox" name="task[]" value="'.$t['id'].'"'.$validate.@$selected[$t['id']].'> '.$t['name'].'<br />';
							}
						?>
					</td>
				</tr>
				
			</table>
			
			<h3>Auftragsinfo</h3>
			
			<table id="form" width="100%" style="border: 0;">
			
				<tr>
					<td style="width: 160px;">Termin</td>
					<td>
						<input type="text" class="box3" name="duedate" id="datetime" data-validate="validate(required)" value="<?=date('d.m.Y H:i', $task['duetime'])?>"><br />
						<small>Beispiel: 08.11.2011 18:00</small>
					</td>
				</tr>
				<tr>
					<td style="vertical-align: top;">Reservieren</td>
					<td colspan="2">
						<select name="reserve" class="box3">
							<?php 
								foreach($reserve as $r) {
									$selected = '';
									if($r['id'] == $task['reserve'])
										$selected = ' selected';
									print '<option value="'.$r['id'].'"'.$selected.'>'.$r['name'].'</option>';
								}
							?>
						</select>									
					</td>
				</tr>
				<tr>
					<td style="vertical-align: top;">Info an</td>
					<td colspan="2">
						<select name="infouser" class="box3">
							<?php 
								foreach($users as $u) {
									$selected = '';
									if($u['uid'] == $task['infouser'])
										$selected = ' selected';
									print '<option value="'.$u['uid'].'"'.$selected.'>'.$u['username'].'</option>';
								}
							?>
						</select>									
					</td>
				</tr>
				<tr>
					<td style="vertical-align: top;">Bemerkungen</td>
					<td colspan="2">
						<textarea name="comments" rows="5" cols="30" class="box"><?=$task['comments']?></textarea>									
					</td>
				</tr>
				
			</table>
			
			<input type="hidden" name="id" value="<?=$id?>" />
			
			<p><input name="submit" type="submit" id="submit" tabindex="5" class="com_btn" value="SPEICHERN"></p>
					
		</form>
	
	<?php
	
	write_footer();
	
?>