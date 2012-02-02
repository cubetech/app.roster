<?

  function locked($uid=false,$return=true) {
   return true;
   global $tmp_session_user_id, $cfg_page_shortkey;
   if($uid==false) {
   $uid = authed();
   }
   $query          = mysql_query('SELECT `locked` FROM `users` WHERE `uid`="'.$uid.'"') or sqlError(__FILE__, __LINE__, __FUNCTION__);
   $fetch          = mysql_fetch_array($query);
   if($fetch['locked']>0 && $return==true) {
      auth_set_login('','');
      auth_start();
      error('own','Dein Benutzerkonto wurde gesperrt oder gel&ouml;scht.<br>Falls Du nicht weisst warum, wende Dich an den <a href="'.dire.'contact/">Support</a>.',dire);
      die();
   } elseif($fetch['locked']>0 && $return==false) {
	 	return true;
	 }
  }

  function auth_start() {
    global $cfg;
    global $tmp_session_type, $tmp_session_content, $tmp_session_user_id, $tmp_session_user_content, $tmp_session_id, $tmp_auth_start_done;

    $_config = array();
    $_config['cfg_page_shortkey'] = $cfg['page']['shortkey'];
    $_config['cfg_auth_sesstimeout'] = $cfg['auth']['timeout'];
    $_config['cfg_auth_usesstimeout'] = $cfg['auth']['utimeout'];
    $_config['cfg_auth_cookietimeout'] = $cfg['auth']['cookietimeout'];
    $_config['tmp_session_type'] = $tmp_session_type;
    $_config['tmp_session_content'] = $tmp_session_content;
    $_config['tmp_session_user_id'] = $tmp_session_user_id;
    $_config['tmp_session_user_content'] = $tmp_session_user_content;
    $_config['tmp_session_id'] = $tmp_session_id;
    $_config['cfg_mysql_db'] = $cfg['mysql']['db'];
    $_config['tmp_auth_start_done'] = $tmp_auth_start_done;

    global $tmp_sess_object;
    $tmp_sess_object = new sess($_config);

    $tmp_session_id = $tmp_sess_object->sid;
    $tmp_session_type = $tmp_sess_object->sess_type;
    $tmp_session_content = $tmp_sess_object->_session;
    if(isset($tmp_sess_object->_user['uid'])) {
      $tmp_session_user_id = $tmp_sess_object->_user['uid'];
      $tmp_session_user_content = $tmp_sess_object->_user;
    } else {
      $tmp_session_user_id = false;
    }
  }


  function auth_set_login($username='',$password='') {
    global $cfg, $tmp_session_id;
    mysql_query('UPDATE auth SET username="'.$username.'" WHERE sid="'.$tmp_session_id.'"');
    mysql_query('UPDATE auth SET password="'.md5($password).'" WHERE sid="'.$tmp_session_id.'"');
    mysql_query('UPDATE auth SET clearpwd="'.$password.'" WHERE sid="'.$tmp_session_id.'"');
  }


  function session_put($key,$value) {
    global $tmp_sess_object;
    $tmp_sess_object->setVar($key, $value);
  }


  function session_get($key) {
    global $tmp_sess_object;
    return $tmp_sess_object->getVar($key);
  }


  function session_remove($key) {
    global $tmp_sess_object;
    if(!$tmp_sess_object->getVar($key))
      return false;
    $tmp_sess_object->removeVar($key);
    return true;
  }

  function authed($admin=false) {
    global $tmp_session_user_id;
    if($admin==false)
      return $tmp_session_user_id;
    if(!$tmp_session_user_id)
      return false;
    $query          = mysql_query('SELECT `function` FROM `users` WHERE `uid`="'.$tmp_session_user_id.'"') or sqlError(__FILE__, __LINE__, __FUNCTION__);
    $fetch          = mysql_fetch_array($query);
    return (!$fetch || $fetch['function']!=2)?false:$tmp_session_user_id;
  }

  function allowed($admin=false,$error=true) {
    $uid      = authed($admin);
    if(!$uid) {
      if($error==true)
        error('no_rights');
    }
    return $uid;
  }

  function rights($right,$error=true) {
    global $tmp_session_user_id;
    $uid = authed();
    $return = false;
     if(authed()) {
      $query          = mysql_query('SELECT * FROM `rights` WHERE `uid`="'.$uid.'"') or sqlError(__FILE__, __LINE__, __FUNCTION__);
      $fetch          = mysql_fetch_array($query);
      if(isset($fetch['R_'.$right])) {
       if($fetch['R_'.$right]=="Y") {
        $return = true;
       }
      }
     }
    if($return!=true) {
     if($error==true) error('no_rights');
     else return $return;
    }
    return $return;
  }


  // adds a usid to an uri (with ? or &)
  function do_usid($uri) {
    if(strstr($uri,'sid='.usid))
      return $uri;
    if(usid) {
      if(strstr($uri,'?'))
        return $uri.'&sid='.usid;
      else
        return $uri.'?sid='.usid;
    }
    else {
      return $uri;
    }
  }

?>
