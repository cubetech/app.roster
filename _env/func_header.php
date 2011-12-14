<?
  // used for writing the header template
  // $align       : the align of the content {'center','right','left','justify'}
  // $title       : title of this page
  // $bodypars    : body-attribut.. { <body$bodypars> }
  // => the template will be stored in $tmp_output_header {~/_env/globals.php} till write_footer() is runned


   function write_header($title='',$align='left',$menu=true,$bodypars='') {
    global $cfg, $tmp_style_path, $tmp_session_user_id;
    global $tmp_output_header, $sidebar;
    $cr           = "\r\n";

    $title      = $cfg['page']['title'].' &raquo; '.$title;
    $_language  = translate('language');
    $_lang      = getLanguage();

    ob_start();

    $authed = '&nbsp;';
    $login = 'login';
    $menu = '&nbsp;';
    $boxmenu = $sidebar;
    
    if(!authed(false)) {
        $boxmenu = '
            <form action="'.dire.'login/login.php" method="post">
                Username:<br />
                <input type="text" name="username" />
                <br /><br />
                Passwort:<br />
                <input type="password" name="password" style="margin-top: 0px;" />
                <br /><br />
                <input type="submit" value="Anmelden" />
            </form>
        ';
    }

    if(authed()) {
        $query = mysql_query('SELECT * FROM `users` WHERE `uid` = "'.$tmp_session_user_id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        $member = mysql_fetch_array($query);
        $query = mysql_query('SELECT COUNT(*) FROM `members` WHERE `uid` = "'.$member['uid'].'" AND `draft` = "1"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        $draft = mysql_fetch_array($query);
        $authed = translate('Welcome').', '.$member['username'];
        $login = translate('logout');
        $menu = '
                    <ul>
                        <li><a href="'.dire.'members/">'.translate('members').'</a></li>
        ';
        if($draft[0]>0) {
            $drafttext = translate('draft');
            if($draft[0]>1)
                $drafttext = translate('drafts');
            $menu .= '<li style="padding-left: 30px;"><small>- <b>'.$draft[0].'</b> '.$drafttext.'</small></li>
            ';
        }
        $menu .= '
                        <li><a href="'.dire.'ucp/">'.translate('your profile').'</a></li>
                    </ul>
                ';
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

    $login  = 'Du bist angemeldet. <a href="'.dire.'login/logout.php">' . translate('logout') . '</a>';

    if(!authed()) {
        $login = '
                <a href="'.dire.'login/">'.translate('login').'</a>
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
