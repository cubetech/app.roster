<?php

  function mdb_connect() {
    global $cfg;
    mysql_connect($cfg['mysql']['host'],$cfg['mysql']['user'],$cfg['mysql']['password'],true) or sqlError(__FILE__, __LINE__, __FUNCTION__);
    mysql_select_db($cfg['mysql']['db']) or sqlError(__FILE__, __LINE__, __FUNCTION__);
  }
  
  
  function mdb_get($query) {
    global $cfg;
    $query          = mysql_query($query);
    if(!$query)
      return false;
    $fetch          = mysql_fetch_array($query);
    $_fields        = array();
    for($i=0;$i<count($fetch);$i++) {
      $_fields[mysql_field_name($query,$i)]       = $_fields[$i];
    }
    return $_fields;
  }



		/**
			* Handles MySQL errors. Prints them and exits.
			* @param file optional __FILE__
			* @param line optional __LINE__
			* @param function optional __FUNCTION__ or __METHOD__
			* @return void
			*/
		function sqlError($file=false, $line=false, $function=false) {
			$message = '<h1>Datenbankfehler</h1><br>';
			$message .= 'Es ist ein Datenbankfehler aufgetreten!<br><br>';
			if($file) {
				$message .= 'In <b>' . $file . '</b>';
				if($line) {
					$message .= ' on line ' . $line;
					if($function) {
						$message .= ' in function  ' . $function;
					}
				}
				$message .= '<br><br>';
			}
			$message .= mysql_error();

  		$mess	= 'Hi Christoph<br><br>This is the automatic SQL error reporting service. <br><br>This error was produced at '.date("d.m.Y H:i").' by the user '.userName(authed()).'. <br><bR>Below you can read the error message:<br><br>';
		echo $mess.$message;
        return $mess.$message;	
			$subject = '[ERROR] fire-pics.ch SQL Error Reporting';
  		$xtra    = "From: error@fire-pics.ch (fire-pics.ch Reporting)\r\n";
  		$xtra   .= "Content-Type: text/html; charset=iso-8859-1\r\n";
   		mail('Christoph Ackermann <c.ackermann@fire-pics.ch>',
        $subject,
        $mess.$message,
        $xtra);

			error('', $message);
		}



?>
