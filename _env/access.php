<?php

//****************************************
// AUTHENTICATION
// based on http://www.php.net/manual/en/features.http-auth.php
//****************************************

//set variables
$realm = 'Ticketpark Lager';

// figure out if we need to challenge the user
if(empty($_SERVER['PHP_AUTH_DIGEST'])){
        header('HTTP/1.1 401 Unauthorized');
        header('WWW-Authenticate: Digest realm="'.$realm.'",qop="auth",nonce="' . uniqid() . '",opaque="' . md5($realm) . '"');
 		 die('access denied');
}//if

// analyze the PHP_AUTH_DIGEST variable
if (!($data = http_digest_parse($_SERVER['PHP_AUTH_DIGEST']))){
     die('access denied');
}//if

//look up password
$username = $cfg['auth']['user'];
$password = $cfg['auth']['pass'];

//is there a password?
if (!isset($password) || !$password){
     die('access denied');
}//if

//username ok?
if (!$data['username'] == $username){
     die('access denied');
}//if



// generate the valid response
$A1 = md5($data['username'] . ':'.$realm.':' . $password);
$A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
$valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);

if($data['response'] != $valid_response){
   die('access denied');
}//if


//****************************************
// HELPER FUNCTION
// found on http://www.php.net/manual/en/features.http-auth.php#93427
//****************************************

function http_digest_parse($txt){
   // protect against missing data
   $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
   $data = array();

   preg_match_all('@(\w+)=(?:(?:\'([^\']+)\'|"([^"]+)")|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

   foreach ($matches as $m) {
       $data[$m[1]] = $m[2] ? $m[2] : ($m[3] ? $m[3] : $m[4]);
       unset($needed_parts[$m[1]]);
   }

   return $needed_parts ? false : $data;
}
