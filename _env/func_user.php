<?

  function userName($uid) {
    global $cfg_page_shortkey;
    if(!$uid)
      return false;
    $query      = mysql_query('SELECT username FROM `users` WHERE uid="'.$uid.'"');
    $fetch      = mysql_fetch_array($query);
    if(!$fetch)
      return false;
    return $fetch[0];
  }

  function userId($uname) {
    global $cfg_page_shortkey;
    if(!$uname)
      return false;
    $query      = mysql_query('SELECT uid FROM `users` WHERE username="'.$uname.'"');
    $fetch      = mysql_fetch_array($query);
    if(!$fetch)
      return false;
    return $fetch[0];
  }

?>
