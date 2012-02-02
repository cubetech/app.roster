<?
    
    define('dire','../');
    include(dire.'_env/exec.php');

    $username     = vGET('username');
    $password     = vGET('password');
    $remember     = vGET('remember');

    if(!$username || !$password) {
        auth_set_login($username,'');
        auth_start();
        error('req_allfields');
    }

    $req          = mysql_query('SELECT * FROM `users` WHERE `username`="'.addslashes($username).'" AND `password`="'.addslashes(md5($password)).'"');
    $fetch        = mysql_fetch_array($req);
    
    if(!$fetch) {
        auth_set_login($username,'');
        auth_start();
        error('unkown_user_pass');
    }

    if($fetch['status']=='false') {
        auth_set_login($username,'');
        auth_start();
        session_start();
        $_SESSION['username'] = $username;
        header('Location: status/');
    }

    auth_set_login($username,$password);
  
    mysql_query('UPDATE `users` SET `lastlogin`="'.time().'" WHERE `username`="'.$username.'" AND `password`="'.md5($password).'"');
    
    if($remember) {
//      setcookie($cfg_page_shortkey.'_auth_csid',$tmp_session_id,time()+$cfg_auth_cookietimeout,'/','',0);
        header('Set-Cookie: auth_csid='.$tmp_session_id.'; expires='.date('r',time()+$cfg_auth_cookietimeout).'; path=/');
    }
    
    auth_start();

	_message('Sie sind jetzt eingeloggt.');

	header("Location: " . dire);

?>
