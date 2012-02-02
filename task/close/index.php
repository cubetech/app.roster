<?php

	define('dire', '../../');
	include(dire . '_env/exec.php');
	
	$id = vGET('id');
	$status = vGET('status');
	
	$query = mysql_query('SELECT * FROM `status` WHERE `id`="'.$status.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
	$statuslist = mysql_fetch_array($query);
	
	if(!is_array($statuslist)) {
		_message('Es wurde ein ung&uuml;ltiger Status gesendet. Aktion nicht ausgef&uuml;hrt.', true);
		header('Location: '.dire.'task/');
		die();
	}
	
	$query = mysql_query('UPDATE `task` SET `status`="'.$status.'" WHERE `id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
	
	$query = mysql_query('SELECT * FROM `task` WHERE `id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
	$task = mysql_fetch_array($query);
	
	$query = mysql_query('SELECT * FROM `users` WHERE `uid`="'.$task['infouser'].'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
	$user = mysql_fetch_array($query);
	
	sendMail($user['mail'], $user['prename'] . ' ' . $user['name'], 'Auftrag wurde '.str_replace('&ouml;', 'ö', $statuslist['text']).'', 'Hallo '.$user['prename'].'<br><br>Der Auftrag Nr. '.$task['id'].' wurde '.$statuslist['text'].'.<br>Details: http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"].'<br><br>Freundliche Gr&uuml;sse', 'html');
	
	_message('Der Auftrag wurde '.$statuslist['text'].'.');
	
	header('Location: '.dire.'task/');
	
?>