<?php

	define('dire', '../../');
	include(dire . '_env/exec.php');

	$id 			= vGET('id');
	$company		= vGET('company');
	$name			= vGET('name');
	$mobile			= vGET('mobile', true, 'string');
	$allpneu_task	= vGET('allpneu_task');
	$tire			= vGET('tire'); //select
	$location		= implode(',', vGET('location')); //checkbox
	$task			= implode(',', vGET('task')); //checkbox
	$date			= explode(' ', vGET('duedate'));
	$duetime		= explode(':', $date[1]);
	$duedate		= explode('.', $date[0]);
	$reserve		= vGET('reserve'); //select
	$infouser		= vGET('infouser'); //select
	$comments		= vGET('comments');

	if(is_array($duedate) && count($duedate) == 3 && is_array($duetime) && count($duetime) == 2) {
		$time			= @mktime($duetime[0], $duetime[1], 0, $duedate[1], $duedate[0], $duedate[2]);
		$isint = true;
		if(!is_int($time))
			$isint = false;
	} else {
		$isint = false;
	}
	if($isint == false) {
		_message('<strong>Warnung:</strong> Das Datum ist ung&uuml;ltig oder liegt in der Vergangenheit!<br />Der Auftrag wurde gespeichert.');
	}
	
	if(!isset($id) || $id == '' || $id < 1) {

		$query 		= mysql_query('INSERT INTO `task` (
															company,
															name,
															mobile,
															allpneu_task,
															tire,
															location,
															task,
															duetime,
															reserve,
															infouser,
															comments
															)	
															VALUES (
																		"'.$company.'",
																		"'.$name.'",
																		"'.$mobile.'",
																		"'.$allpneu_task.'",
																		"'.$tire.'",
																		"'.$location.'",
																		"'.$task.'",
																		"'.(int)$time.'",
																		"'.$reserve.'",
																		"'.$infouser.'",
																		"'.$comments.'"
																	)') or sqlError(__FILE__,__LINE__,__FUNCTION__);
																	
	} else {
	
		$query		= mysql_query('UPDATE `task` SET
															company = "'.$company.'",
															name = "'.$name.'",
															mobile = "'.$mobile.'",
															allpneu_task = "'.$allpneu_task.'",
															tire = "'.$tire.'",
															location = "'.$location.'",
															task = "'.$task.'",
															duetime = "'.(int)$time.'",
															reserve = "'.$reserve.'",
															infouser = "'.$infouser.'",
															comments = "'.$comments.'"
												 WHERE		`id` = "'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
	
	}
																
	if($isint != false) { _message('Der Auftrag wurde erfolgreich gespeichert!'); };
	
	header("Location: ".dire."task/");
	
?>