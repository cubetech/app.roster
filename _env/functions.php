<?
  
  function fileCheck($fileName) {
    global $cfg;
    $end = $cfg['file'];
    $tmparray = explode(',',$end['type']);
    for($i=0;$i<count($tmparray);$i++) {
        $e = $end[$tmparray[$i]];
        $tmp = explode(',',$e);
        foreach($tmp as $t) {
            $file = explode('.',$fileName);
            $file = $file[(count($file)-1)];
            if($file == $t)
                $output = $tmparray[$i];
            }
        }
        return $output;
    }
  
  function sendMail($mail,$name,$subject,$message,$mail_type='text') {
    if($mail_type=='text') {
      $mail_to      = $name." <".$mail.">";
      $mail_header    = "From:\"svnhost.ch\" <mailer@svnhost.ch>\n";
      $mail_header   .= "Content-Type: text/plain";

      if(!mail($mail_to,$subject,$message,$mail_header))
        error('transfer');
    }
  }

  function getTemplate($filename,$directory=false) {
    if(!$directory) {
      global $cfg, $tmp_style_path;
      $directory      = $tmp_style_path.'templates/';
    }
    if($directory{strlen($directory)-1}!='/' && $directory{strlen($directory)-1}!='\\')
      $directory     .= '/';
    if($directory{strlen($directory)-1}=='\\')
      $directory      = substr($directory,0,strlen($directory)-1).'/';
    $code             = implode("",file($directory.$filename));
    $code             = str_replace("\"","\\\"",$code);
    $code             = str_replace("%dire%",dire,$code);
    $code             = str_replace("%stylepath%",$tmp_style_path,$code);
/*    $code             = str_replace('%usid%',usid,$code);
    $code             = str_replace('%usid1%',usid1,$code);
    $code             = str_replace('%usid2%',usid2,$code);
*/    return $code;
  }

  function vGET($str,$safe=true,$type=false,$strtype=false,$ent=true) {
    // \/
    if(!function_exists('vGET_parse')) {
      function vGET_parse($foo) {
        if(is_array($foo)) {
          foreach($foo as $k=>$v) {
            $foo[$k]    = vGET_parse($foo[$k]);
          }
        }
        elseif(is_scalar($foo)) {
          if(is_numeric($foo))
            $foo        = (float) $foo;
          elseif(is_string($foo))
            $foo        = (string) stripslashes($foo);
        }
        elseif(is_null($foo))
          $foo          = NULL;
        return $foo;
      }
    }
    //  ^^
    global $_GET, $_POST, $_COOKIE, $_FILES, $_SESSION;
    $_strings         = array('get'=>$_GET,'post'=>$_POST,'cookie'=>$_COOKIE,'files'=>$_FILES,'session'=>$_SESSION);
    foreach($_strings as $key=>$value) {
      if((!$type || $type==$key) && isset($value[$str])) {
        $v            = $value[$str];
        ${$str}       = vGET_parse($v);
        $strtype      = gettype(${$str});
        if($key=='files' || $key=='session')
         return ${$str};
        else{
         if(is_array(${$str}))
          return ${$str};
         elseif($ent==false || $safe==true)
				  return ${$str};
		 else
          return htmlentities(${$str});
        }
      } elseif(isset($value[$str])) {
      	return htmlentities($value[$str]);
      }
    }
    return false;
  }

  // searching a string for possible URL's and E-Mail-Addys and replacing it with a HTML-Link
  // targets can be defined with the variables $cfg_parseurl_url and $cfg_parseurl_mail (~ _env/config.php)
  function parseURL($out) {
    $urlsearch[]="/([^]_a-z0-9-=\"'\/])((https?|ftp):\/\/|www\.)([^ \r\n\(\)\*\^\$!`\"'\|\[\]\{\};<>]*)/si";
    $urlsearch[]="/^((https?|ftp):\/\/|www\.)([^ \r\n\(\)\*\^\$!`\"'\|\[\]\{\};<>]*)/si";
    $urlreplace[]="\\1<a href=\"".dire."go/?target=\\2\\4\" target=\"_blank\">\\2\\4</a>";
    $urlreplace[]="<a href=\"".dire."go/?target=\\1\\3\" target=\"_blank\">\\1\\3</a>";
    $emailsearch[]="/([\s])([_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,}))/si";
    $emailsearch[]="/^([_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,}))/si";
    $emailreplace[]="\\1<a href=\"mailto:\\2\">\\2</a>";
     $emailreplace[]="<a href=\"mailto:\\0\">\\0</a>";
    $out = preg_replace($urlsearch, $urlreplace, $out);
    if (strpos($out, "@")) $out = preg_replace($emailsearch, $emailreplace, $out);
    return $out;
  }
  
    function siteGen($page, $count) {
        global $cfg;
        $out = " ";
        $pages = ceil($count/$cfg['page']['pagesteps']);
        if($page>1)
            $out .= '<a href="?page='.($page-1).'" class="button">&laquo;</a>';
        else
            $out .= '<a class="button inactive">&laquo;</a>';
        for($i=1;$i<=$pages;$i++) {
            $class = "";
            if($i==$page)
                $class = " bold";
            $out .= '&nbsp;<a href="?page='.$i.'" class="button'.$class.'">'.$i.'</a>';
        }
        if($page<$pages)
            $out .= '&nbsp;<a href="?page='.($page+1).'" class="button">&raquo;</a>';
        else
            $out .= '&nbsp;<a class="button inactive">&raquo;</a>';
        return $out;
    }
    
    function getSize($size, $round=1) {
      $si = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
      return round($size / pow(1024, ($i = floor(log($size, 1024)))), $round) . ' ' . $si[$i];
    }
    
    function restTime($time,$text=' verbleiben') {
        global $cfg;
        if(($time/31536000)>=1)
            return bcdiv($time, 31536000, 0) . ' Jahre'.$text;
        elseif(($time/2630520)>=1)
            return bcdiv($time, 2630520, 0) . ' Monate'.$text;
        elseif(($time/86400)>=1)
            return '<font color="'.$cfg['style']['expiresoon'].'">'.bcdiv($time, 86400, 0) . ' Tage'.$text.'</font>';
        elseif($time>1)
            return '<font color="'.$cfg['style']['expire'].'">l&auml;uft heute ab</font>';
        else
            return '<font color="'.$cfg['style']['expired'].'">ist abgelaufen</font>';
    }

?>
