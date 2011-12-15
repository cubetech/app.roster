<?php

	define('dire', '../');
	include(dire . '_env/exec.php');
	
	write_header('Auftr&auml;ge');
	
	?>
	
		<table width="850" border="0" cellspacing="0" cellpadding="10" class="table_main">
		  <tbody><tr style="background-color:#d9d8d8; font-size:14px;">
			<td width="179"><strong>USER</strong></td>
			<td width="184"><strong>EMAIL</strong></td>
			<td width="273"><strong>SOMETHING</strong></td>
			<td width="132"><strong>DO IT</strong></td>
		  </tr>
		  <tr class="gray">
			<td>Bruce Lee </td>
			<td><a href="#">bruce@kungfu.com</a></td>
			<td>Loriem ipsum dolor sit amet </td>
			<td><a href="#">EDIT  </a><span class="v_line">| </span> <a href="#" class="delete">DELETE </a></td>
		  </tr>
		  <tr>
			<td>Jackie Chan</td>
			<td><a href="#">thechan@yahoo.com</a></td>
			<td>Loriem ipsum dolor sit amet </td>
			<td><a href="#">EDIT  </a><span class="v_line">| </span> <a href="#" class="delete">DELETE </a></td>
		  </tr>
		  <tr class="gray">
			<td>John Claude Van Damme</td>
			<td><a href="#">vandamme@gmail.com</a></td>
			<td>Loriem ipsum dolor sit amet </td>
			<td><a href="#">EDIT  </a><span class="v_line">| </span> <a href="#" class="delete">DELETE </a></td>
		  </tr>
		   <tr>
			<td>Ben Johnson </td>
			<td><a href="#">ben@kungu.com</a></td>
			<td>Loriem ipsum dolor sit amet </td>
			<td><a href="#">EDIT  </a><span class="v_line">| </span> <a href="#" class="delete">DELETE </a></td>
		  </tr>
		</tbody></table>
		
	<?php
	
	write_footer();
	
?>