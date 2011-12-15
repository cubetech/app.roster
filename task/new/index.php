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
	
	write_header('Neuer Auftrag erfassen');
	
	?>
	
		<form method="post" action="<?=dire?>task/save/">
			
			<div class="formpoint">
		
			<h3>Kunde</h3>
		
			<table id="form" width="100%" style="border: 0;">
			
				<tr>
					<td>Firma</td>
					<td><input type="text" class="box3" name="company"></td>
					<td>Name</td>
					<td><input type="text" class="box3" name="name"></td>
				</tr>
				<tr>
					<td>Natel</td>
					<td><input type="text" class="box3" name="mobile"></td>
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
							foreach($location as $l)
								print '<input type="checkbox" name="location" value="'.$l['id'].'"> '.$l['name'].'<br />';
						?>
					</td>
				</tr>
				<tr>
					<td style="vertical-align: top;">Auftrag</td>
					<td>
						<input type="checkbox" name="task" value=""> Montage<br />
						<input type="checkbox"> Auswuchten<br />
						<input type="checkbox"> Waschen<br />
						<input type="checkbox"> Reparatur<br />
						<input type="checkbox"> Spikes<br />
						<input type="checkbox"> Reservieren<br />
						<input type="checkbox"> Felgen Reparatur<br />
						<input type="checkbox"> Swissfill Dichtung<br />
						<input type="checkbox"> Swissfill F&uuml;llung<br />
					</td>
				</tr>
				
			</table>
			
			<h3>Auftragsinfo</h3>
			
			<table id="form" width="100%" style="border: 0;">
			
				<tr>
					<td style="width: 160px;">Termin</td>
					<td style="width: 120px;">
						<input type="text" class="box4" name="duedate"><br />
						<small>Beispiel: 08.11.2011</small>
					</td>
					<td>
						<input type="text" class="box5" name="duetime"><br />
						<small>Beispiel: 14:30</small>
				</tr>
				<tr>
					<td style="vertical-align: top;">Reservieren</td>
					<td colspan="2">
						<select name="reserve" class="box3">
							<option selected="selected">Reservationsgestell</option>
							<option>Warenausgang</option>
							<option>Box RS</option>
							<option>Box HUM</option>
							<option>Box DZ</option>
						</select>									
					</td>
				</tr>
				<tr>
					<td style="vertical-align: top;">Info an</td>
					<td colspan="2">
						<select name="infouser" class="box3">
							<option selected="selected">info</option>
							<option>OF</option>
							<option>TD</option>
							<option>PE</option>
							<option>DZ</option>
							<option>RS</option>
							<option>HUM</option>
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