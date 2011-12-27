<?
  // used for writing the header template
  // $align       : the align of the content {'center','right','left','justify'}
  // $title       : title of this page
  // $bodypars    : body-attribut.. { <body$bodypars> }
  // => the template will be stored in $tmp_output_header {~/_env/globals.php} till write_footer() is runned


   function write_header($title='',$line='',$menu=true,$bodypars='') {
    global $cfg, $tmp_style_path, $tmp_session_user_id;
    global $tmp_output_header, $sidebar;
    $cr           = "\r\n";

    $less_title	= $title;
    $title      = $cfg['page']['title'].' &raquo; '.$title;

    ob_start();

    $authed = '&nbsp;';
    $login = 'login';
    $_message = _message_get();
    $_complete = '';
    
    $uri = explode('/', $_SERVER["REQUEST_URI"]);
    if(in_array('task', $uri) && in_array('new', $uri)) {
    	$high_new = ' class="active"';
    } elseif(in_array('task', $uri) && !in_array('?status=2', $uri)) {
    	$high_task = ' class="active"';
    } elseif(in_array('search', $uri)) {
    	$high = '';
    } else {
    	$high = ' class="active"';
    }
    
    if(@$_SESSION['complete']==true) {
    	$query = mysql_query('SELECT * FROM `task`');
    	$tasks = array();
    	while($fetch=mysql_fetch_array($query))
    		array_push($tasks, $fetch);
    		
    	$company = '';
    	$name = '';
    	$mobile = '';
    		
    	foreach($tasks as $t) {
    		$company .= '
    		"'.$t['company'].'",';
    		$name .= '
    		"'.$t['name'].'",';
    		$mobile .= '
    		"'.$t['mobile'].'",';
    	}
    	
    	$_complete = '
		<script>
			
			$(function() {
				var company = ['.$company.'
				    ];
				var name = ['.$name.'
				    ];
				var mobile = ['.$mobile.'
					];
				$( "#company" ).autocomplete({
					source: jQuery.unique(company)
				});
				$( "#name" ).autocomplete({
					source: jQuery.unique(name)
				});
				$( "#mobile" ).autocomplete({
					source: jQuery.unique(mobile)
				});
			});
		</script>
		';
		@$_SESSION['complete'] = false;
	}
		    
    $menu = '
    <li><a href="'.dire.'task/"'.@$high_task.'>OFFENE AUFTR&Auml;GE</a></li>
    <li><a href="'.dire.'task/?status=2"'.@$high.'>ERLEDIGTE AUFTR&Auml;GE</a></li>
    <li><a href="'.dire.'task/new/"'.@$high_new.'>NEUER AUFTRAG ERFASSEN</a></li>
    ';
    
    if(!authed(false)) {
        $login_menu = '<a href="'.dire.'login/">Anmelden</a>';
    }

    if(authed()) {
        $query = mysql_query('SELECT * FROM `users` WHERE `uid` = "'.$tmp_session_user_id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        $member = mysql_fetch_array($query);
        $login_menu = 'Angemeldet als <strong>'.$member['username'] . '</strong> | <a href="'.dire.'login/logout.php">Abmelden</a>';
    }

    // check if there is a file of the style which should been included
    if(is_file($tmp_style_path.'_config/config_header.php'))
      include($tmp_style_path.'_config/config_header.php');

    // get template
    eval("echo \"".getTemplate('header.html')."\";");
    // store code
    $tmp_output_header    = ob_get_contents();
    ob_end_clean();
    ob_start();
  }

  // writes the footer template and sends the whole sitecontent to the browser (bundeled)
  function write_footer($notop='') {
    global $tmp_output_header, $tmp_output_content, $tmp_output_footer;
    global $tmp_style_path;
    global $tmp_session_content;

    // store content code (and add code indent)
    $tmp_output_content   = ob_get_contents();
    $tmp_output_content   = str_replace("\r\n","\n",$tmp_output_content);
    $tmp_output_content   = str_replace("\r","\n",$tmp_output_content);
    $tmp_output_content   = str_replace("\n","\r\n",$tmp_output_content);
    $tmp_output_content   = str_replace('%%textarea_carriage_return%%',"\r\n",$tmp_output_content);
    ob_end_clean();
    ob_start();

    // check if there is a file of the style which should been included
    if(is_file($tmp_style_path.'_config/config_footer.php'))
      include($tmp_style_path.'_config/config_footer.php');

    $login  = 'Du bist angemeldet. <a href="'.dire.'login/logout.php">Abmelden</a>';

    if(!authed()) {
        $login = '
                <a href="'.dire.'login/">Anmelden</a>
                ';
    }

    // get template
    eval("echo \"".getTemplate('footer.html')."\";");
    // store code
    $tmp_output_footer    = ob_get_contents();
    ob_end_clean();
    // print code
    $output_code          = $tmp_output_header.$tmp_output_content.$tmp_output_footer;
    if($notop=='') {
     $output_code          = str_replace('%top%','<div align="right"><a href="#top">^ zum Seitenanfang</a></div>',$output_code);
    } else {
     $output_code          = str_replace('%top%','',$output_code);
    }
    // mask @
    $tmp = str_replace("@","&#x0040;",$output_code);
    echo str_replace("&#x0040;import", "@import", $tmp);
  }

?>
