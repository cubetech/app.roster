<?php

	define('dire', '../../');
	include(dire . '_env/exec.php');
	
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

	write_header('Neuer Auftrag erfassen');
	
	$_SESSION['complete'] = true;
	
	?>
	
		<form id="form" method="post" action="<?=dire?>task/save/">
			
			<div class="formpoint">
		
			<h3>Kunde</h3>
		
			<table id="form" width="100%" style="border: 0;">
			
				<tr>
					<td>Firma</td>
					<td><input type="text" class="box3" name="company" id="company"/></td>
					<td>Name</td>
					<td><input type="text" class="box3" name="name" id="name" data-validate="validate(required, minlength(3))" /></td>
				</tr>
				<tr>
					<td>Natel</td>
					<td><input type="text" class="box3" name="mobile" id="mobile" data-validate="validate(required, digits)"></td>
					<td>Auftragsnr.<br>AllPneu</td>
					<td><input type="text" class="box3" name="allpneu_task"></td>
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
								foreach($tire as $t)
									print '<option value="'.$t['id'].'">'.$t['name'].'</option>';
							?>
						</select>									
					</td>
				</tr>
				<tr>
					<td style="vertical-align: top;">Standort</td>
					<td>
						<?php 
							for($i=0; $i<count($location); $i++) {
								$l = $location[$i];
								$validate = '';
								if($i===0)
									$validate = ' data-validate="validate(minselect(1))"';
								print '<input type="checkbox" name="location[]" value="'.$l['id'].'"'.$validate.'> '.$l['name'].'<br />';
							}
						?>
					</td>
				</tr>
				<tr>
					<td style="vertical-align: top;">Auftrag</td>
					<td>
						<?php 
							for($i=0; $i<count($tasklist); $i++) {
								$t = $tasklist[$i];
								$validate = '';
								if($i===0)
									$validate = ' data-validate="validate(minselect(1))"';
								print '<input type="checkbox" name="task[]" value="'.$t['id'].'"'.$validate.'> '.$t['name'].'<br />';
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
						<input type="text" class="box3" name="duedate" id="datetime" data-validate="validate(required)"><br />
						<small>Beispiel: 08.11.2011 18:00</small>
					</td>
				</tr>
				<tr>
					<td style="vertical-align: top;">Reservieren</td>
					<td colspan="2">
						<select name="reserve" class="box3">
							<?php 
								foreach($reserve as $r)
									print '<option value="'.$r['id'].'">'.$r['name'].'</option>';
							?>
						</select>									
					</td>
				</tr>
				<tr>
					<td style="vertical-align: top;">Info an</td>
					<td colspan="2">
						<select name="infouser" class="box3">
							<?php 
								foreach($users as $u)
									print '<option value="'.$u['uid'].'">'.$u['username'].'</option>';
							?>
						</select>									
					</td>
				</tr>
				<tr>
					<td style="vertical-align: top;">Bemerkungen</td>
					<td colspan="2">
						<textarea name="comments" rows="5" cols="30" class="box"></textarea>									
					</td>
				</tr>
				
			</table>
			
			<p><input name="submit" type="submit" id="submit" tabindex="5" class="com_btn" value="SPEICHERN"></p>
					
		</form>
	
	<?php
	
	write_footer();
	
?>