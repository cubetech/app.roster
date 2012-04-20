<?php

    function error($errcode=false, $errstr=false, $btntarget='javascript:history.back();', $btnstr='Okay', $header='write') {
      $_errcodes    = array(
        'unknown'=>'Ein unbekannter Fehler ist aufgetreten!',
        'req_allfields'=>'Es m&uuml;ssen alle Felder ausgef&uuml;llt werden!',
        'req_starfields'=>'Die mit * gekennzeichneten Felder m&uuml;ssen ausgef&uuml;llt werden!',
        'unkown_user_pass'=>'Unbekannter Benutzer oder falsches Passwort!',
        'no_rights'=>'Du hast nicht gen&uuml;gend Rechte oder bist nicht eingeloggt!',
        'transfer'=>'Ein &Uuml;bertragungsfehler ist aufgetreten.',
        'req_allcorrect'=>'Alle Felder m&uuml;ssen korrekt ausgef&uuml;llt werden!',
      );
      if($errcode && !isset($_errcodes[$errcode]))
        $errcode    = false;
      if(!$errcode && !$errstr)
        $errcode    = 'unknown';
      if($errcode && isset($_errcodes[$errcode]))
        $output     = $_errcodes[$errcode];
      else
        $output     = $errstr;
    		global $tmp_output_header, $tmp_output_content;
    		if(strlen($tmp_output_header)>0) {
    			$tmp_output_header = '';
    			ob_end_clean();
    		}
        write_header('Fehler');
      echo $output.'<br /><br />
      <a class="btn btn-primary" href="' . $btntarget . '" title="' . $btnstr . '">
          <i class="icon-ok icon-white"></i> ' . $btnstr . '
      </a>';
      write_footer();
      die();
    }

    function quest($queststr=false, $yestarget='#', $yesstr='Ja', $notarget='javascript:history.back();', $nostr='Nein', $header='write') {
        $output     = $queststr;
			global $tmp_output_header, $tmp_output_content;
			if(strlen($tmp_output_header)>0) {
				$tmp_output_header = '';
				ob_end_clean();
			}
	    write_header('Frage');
      echo $output.'<br /><br /><a href="'.$notarget.'"><input type="button" value="&laquo; '.$nostr.'" onClick="location=\''.$notarget.'\'; return false;"></a>&nbsp;<a href="'.$yestarget.'"><input type="button" value="'.$yesstr.' &raquo;" onClick="location=\''.$yestarget.'\'; return false;"></a>';
      write_footer();
      die();
    }
    
    function _message($message, $type = false) {
    
    	if($type == false) {
    		$type = 'good';
    	} else {
    		$type = 'bad';
    	}
    
    	$_SESSION['message'] = $message;
    	$_SESSION['message_type'] = $type;
    	$_SESSION['message_show'] = true;
    	
    }
    
    function _message_get() {
    
    	global $tmp_style_path;
    	
    	$message = @$_SESSION['message'];
    	$type = @$_SESSION['message_type'];
    	$show = @$_SESSION['message_show'];
    	
    	if($show === true) {
    		$icon = 'x';
    		if($type == 'good')
    			$icon = 'check';
	    	$output = '
	    	<div class="check_main">
	    			
	    		<div class="check">
	    			<div class="'.$type.'"><img src="'.$tmp_style_path.'icons/'.$icon.'.gif" alt="check" class="icon">'.$message.'</div>
	    		</div>
	    	</div>
	    	';
	    	$_SESSION['message_show'] = false;
	    	return $output;
    	}
    	
	}

?>
