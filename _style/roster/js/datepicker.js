jQuery(function($){
	$.datepicker.regional['de-CH'] = {
		closeText: 'schliessen',
		prevText: '&#x3c;zur&uuml;ck',
		nextText: 'n&auml;chster&#x3e;',
		currentText: 'heute',
		monthNames: ['Januar','Februar','M&auml;rz','April','Mai','Juni',
		'Juli','August','September','Oktober','November','Dezember'],
		monthNamesShort: ['Jan','Feb','M&auml;r','Apr','Mai','Jun',
		'Jul','Aug','Sep','Okt','Nov','Dez'],
		dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
		dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
		dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
		weekHeader: 'Wo',
		dateFormat: 'dd.mm.yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['de-CH']);
});    	
$.datepicker.setDefaults($.datepicker.regional['de-CH']);
$(function() {
	$( "#datepicker" ).datepicker();
	$( "#startdate" ).datepicker();
	$( "#duedate" ).datepicker();
});
