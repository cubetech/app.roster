<?
  class sess {
    var $sid;
    var $sess_type; // unused, i think. but the table has a column for it :)
    private $config = array();
    private $_vars = array();
    var $_session = array(); // From fetch
    var $_user = array(); // From fetch
    
    public function sess($in_conf) {
      $this->config = $in_conf;
      $pagekey = $in_conf['cfg_page_shortkey'];
      
      $this->cleanSessions();
      
      $sid_u = vGET('sid');
      $sid_t = vGET($pagekey.'_auth_tsid', 'cookie');
      $sid_c = vGET($pagekey.'_auth_csid', 'cookie');
      
      $reg_u = false;
      $reg_t = false;
      $reg_c = false;
      
      if(!$sid_u && !$sid_t && !$sid_c) {
        $reg_u = true;
        $reg_t = true;
        $this->createSession();
        $this->sess_type = 'u';
      }
      elseif($sid_u && $sid_t && $sid_u==$sid_t) {
        $this->sid = $sid_t;
        $this->sess_type = 't';
      }
      elseif($sid_u) {
        $reg_u = true;
        $this->sid = $sid_u;
        $this->sess_type = 'u';
      }
      elseif($sid_t) {
        $this->sid = $sid_t;
        $this->sess_type = 't';
      }
      elseif($sid_c) {
        $reg_c = true;
        $this->sid = $sid_c;
        $this->sess_type = 'c';
      }
      
      $this->loadSession();
      
      if(!defined('usid')) {
        define('usid', ($reg_u ? $this->sid : '') );
        define('usid1', ($reg_u ? '?sid='.$this->sid : '') );
        define('usid2', ($reg_u ? '&sid='.$this->sid : '') );
      }
      if($reg_t) {
        header('Set-Cookie: '.$pagekey.'_auth_tsid='.$this->sid.'; path=/');
      }
      if($reg_c) {
        header('Set-Cookie: '.$pagekey.'_auth_csid='.$this->sid.'; expires='.date('r',time()+$this->config['cfg_auth_cookietimeout']).'; path=/');
      }
      
      $this->checkAuthentification();
    }
    
    private function createSession($sid=false) {
      if(!$sid or $this->sidExists($sid)) {
        $this->sid = $this->generateSid();
      } else {
        $this->sid = $sid;
      }
      $_fields = array(
        'sid'=>$this->sid,
        'session_type'=>$this->sess_type,
        'user_ip'=>$_SERVER['REMOTE_ADDR'],
        'user_hostname'=>$_SERVER['REMOTE_ADDR'],
        'lastaction'=>time(),
        'revisit'=>time()
      );
      $_keys = array();
      $_values = array();
      foreach($_fields as $k=>$v) {
        array_push($_keys, $k);
        array_push($_values, $v);
      }
      $query = 'INSERT INTO `auth` (`';
      $query .= implode('`,`',$_keys).'`) VALUES ("';
      $query .= implode('","',$_values).'")';
      mysql_query($query) or sqlError(__FILE__, __LINE__, __FUNCTION__);
      if(mysql_affected_rows()==0) {
        // did not work. maybe sid alredy existing.. retry
        $this->createSession();
      }
    }
    
    private function generateSid() {
      $generate = true;
      $iteration = 1;
      while($generate) {
        $id = md5(time()*rand()*$iteration++);
        if(!$this->sidExists($id)) {
          $generate = false;
        }
      }
      return $id;
    }

    private function sidExists($sid) {
        $sql = mysql_query('SELECT * FROM auth WHERE sid="'.$sid.'"') or sqlError(__FILE__, __LINE__, __FUNCTION__);
        if(mysql_num_rows($sql)==0) {
            return false;
        } else {
            return true;
        }
    }
    
    private function cleanSessions() {
      mysql_query('DELETE FROM auth WHERE session_type="t" AND lastaction<'.(time()-$this->config['cfg_auth_sesstimeout'])) or sqlError(__FILE__, __LINE__, __FUNCTION__);
      mysql_query('DELETE FROM auth WHERE session_type="c" AND lastaction<'.(time()-$this->config['cfg_auth_sesstimeout'])) or sqlError(__FILE__, __LINE__, __FUNCTION__);
      mysql_query('DELETE FROM auth WHERE session_type="u" AND lastaction<'.(time()-$this->config['cfg_auth_usesstimeout'])) or sqlError(__FILE__, __LINE__, __FUNCTION__);
    }
    
    private function loadSession() {
      $query = mysql_query('SELECT * FROM `auth` WHERE `sid`="'.$this->sid.'"') or sqlError(__FILE__, __LINE__, __FUNCTION__);
      $_sess = mysql_fetch_array($query);
      if(!$_sess && strlen($this->sid)==32) {
        $this->createSession($this->sid);
      }
      elseif(!$_sess) {
        $this->createSession();
      }
      $this->_session = $_sess;
      $this->loadVar($_sess['session']);
      // Update times
      mysql_query('UPDATE `auth` SET `lastaction`='.time().', ' .
                  '`session_type`="'.$this->sess_type.'" WHERE `sid`="'.$this->sid.'"') or sqlError(__FILE__, __LINE__, __FUNCTION__);
    }
   
    private function checkAuthentification() {
      $_user = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `username`="'.$this->_session['username'].'" AND `password`="'.$this->_session['password'].'"'));
      if($_user) {
        $this->_var['userId'] = $_user['uid'];
        mysql_query('UPDATE `users` '.
                    'SET `lastaction`='.time().', ip="'.$_SERVER['REMOTE_ADDR'].'", '.
                    '`hostname`="'.$_SERVER['REMOTE_ADDR'].'" WHERE `uid`="'.$_user['uid'].'"');
        $this->_user = $_user;
      }
    }
    
    private function loadVar($vars) {
      if(!$vars)
        return false;
      $_vars = explode("\r\n", $vars);
      $this->_vars = array();
      foreach($_vars as $v) {
        $l = explode('=', trim($v));
        $this->_vars[$l[0]] = $l[1];
      }
    }
    
    private function saveVar() {
      $_lines = array();
      foreach($this->_vars as $k=>$v) {
        array_push($_lines, $k.'='.$v);
      }
      $vars = implode("\r\n", $_lines);
      mysql_query('UPDATE `auth` SET `session`="'.$vars.'" WHERE sid="'.$this->sid.'"') or sqlError(__FILE__, __LINE__, __FUNCTION__);
    }
    
    public function getVar($key) {
      if(isset($this->_vars[$key])) {
        return $this->_vars[$key];
      } else {
        return null;
      }
    }
    
    public function setVar($key, $value) {
      $this->_vars[$key] = $value;
      $this->saveVar();
    }
    
    public function removeVar($key) {
      $_newvars = array();
      foreach($this->_vars as $k=>$v) {
        if($k!=$key)
          $_newvars[$k] = $v;
      }
      $this->_vars = $_newvars;
      $this->saveVar();
    }
    
  }
?>
