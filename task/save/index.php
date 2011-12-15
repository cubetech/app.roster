<?php

	define('dire', '../../');
	include(dire . '_env/exec.php');

	$company		= vGET('company');
	$name			= vGET('name');
	$mobile			= vGET('mobile');
	$allpneu_task	= vGET('allpneu_task');
	$type			= vGET('type'); //select
	$location		= vGET('location'); //checkbox
	$task			= vGET('task'); //checkbox
	$duedate		= vGET('duedate');
	$duetime		= vGET('duetime');
	$reserve		= vGET('reserve'); //select
	$infouser		= vGET('infouser'); //select
	$comments		= vGET('comments');
	
?>