<?
  
	function sendMail($mail,$name,$subject,$message,$mail_type='text') {
	
		global $cfg;
		
		if($mail_type=='text') {
			$mail_to    = $name." <".$mail.">";
			$header     = 'From: ' . $cfg['mail']['from'] . ' <' . $cfg['mail']['fromaddress'] . '>' . "\r\n" .
			              'Reply-To: ' . $cfg['mail']['from'] . ' <' . $cfg['mail']['fromaddress'] . '>' . "\r\n" .
			              'X-Mailer: PHP/' . phpversion();
		} elseif($mail_type=='html') {
			$mail_to    = $name." <".$mail.">";
			$header     = 'MIME-Version: 1.0' . "\r\n" .
			              'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
			              'From: ' . $cfg['mail']['from'] . ' <' . $cfg['mail']['fromaddress'] . '>' . "\r\n" .
			              'Reply-To: ' . $cfg['mail']['from'] . ' <' . $cfg['mail']['fromaddress'] . '>' . "\r\n" .
			              'X-Mailer: PHP/' . phpversion();
		}

		if(!mail($mail_to,'=?UTF-8?B?'.base64_encode($subject).'?=',$message,$header,'-f '.$cfg['mail']['fromaddress']))
			error('transfer');
			
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
  
  function iGET($str,$safe=true,$type=false,$strtype=false,$ent=true) {
  
      $var = vGET($str, $safe, $type, $strtype, $ent);
      if(!$var || $var=='') {
          error('transfer');
      }
      
      return $var;
  
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
    
    function autoinclude($path) {
        $getpath = opendir($path);
        while($includepath = readdir($getpath)) {
            if(is_dir($path . $includepath) && $includepath != '.' && $includepath != '..') {
                $incpath = opendir($path . $includepath);
                while($includefile = readdir($incpath)) {
                    if($includefile == 'index.php') {
                        include($path . '/' . $includepath . '/' . $includefile);
                    }
                }
            }
        }
    }
    
    function code13($code = false) {
    
        if(!$code) {
            $code = code13_check(substr(number_format(time() * rand(),0,'',''),0,12));
        }
        $query = mysql_query('SELECT * FROM barcode WHERE `barcode`="'.$code.'"') OR sqlError(__FILE__,__LINE__,__FUNCTION__);
        $fetch = mysql_fetch_array($query);
        if($fetch)
            $code = code13();
        else
            $query = mysql_query('INSERT INTO barcode (barcode, time) VALUES ("'.$code.'", "'.time().'")') OR sqlError(__FILE__,__LINE__,__FUNCTION__);
            return mysql_insert_id();
        
    }
    
    function code13_check($digits){
        //first change digits to a string so that we can access individual numbers
        $digits =(string)$digits;
        // 1. Add the values of the digits in the even-numbered positions: 2, 4, 6, etc.
        $even_sum = $digits{1} + $digits{3} + $digits{5} + $digits{7} + $digits{9} + $digits{11};
        // 2. Multiply this result by 3.
        $even_sum_three = $even_sum * 3;
        // 3. Add the values of the digits in the odd-numbered positions: 1, 3, 5, etc.
        $odd_sum = $digits{0} + $digits{2} + $digits{4} + $digits{6} + $digits{8} + $digits{10};
        // 4. Sum the results of steps 2 and 3.
        $total_sum = $even_sum_three + $odd_sum;
        // 5. The check character is the smallest number which, when added to the result in step 4,  produces a multiple of 10.
        $next_ten = (ceil($total_sum/10))*10;
        $check_digit = $next_ten - $total_sum;
        return $digits . $check_digit;
    }
    
    function gen_query($data) {
    
        $names = '(';
        $values = '(';
        
        foreach($data as $name => $value) {
        
            if($name=='datepicker') {
                $value = explode('.', $value);
                $value = mktime(0, 0, 0, $value[1], $value[0], $value[0]);
                $name = 'buydate';
            }
        
            $names .= ''.$name.',';
            $values .= '
            \''.$value . '\',';
            
        }
        
        if(substr($names, -1, 1)==',') {
            $names = substr($names, 0, -1);
        }
        
        if(substr($values, -1, 1)==',') {
            $values = substr($values, 0, -1);
        }
    
        $result = array('names'=>$names, 'values'=>$values);
        
        return $result;
        
    }
    
    function gen_update_query($data) {
    
        $query = '';
    
        foreach($data as $name => $value) {
        
            if($name=='datepicker') {
                $value = explode('.', $value);
                $value = mktime(0, 0, 0, $value[1], $value[0], $value[0]);
                $name = 'buydate';
            }
        
            $query .= '
            '.$name.'="'.$value.'",';
        }
                
        if(substr($query, -1, 1)==',') {
            $query = substr($query, 0, -1);
        }
                
        return $query;
    
    }
    
    function set_message($title, $message, $type='alert-success') {
        $_SESSION['set_message'] = array('title'=>$title, 'message'=>$message, 'type'=>$type);
    }
    
    function unset_message() {
        $_SESSION['set_message'] = '';
    }
    
?>
