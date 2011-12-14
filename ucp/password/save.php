<?
    
    define('dire','../../');
    include(dire.'_env/exec.php');

    allowed();
    session_start();

    $uid = authed();
    if(!$uid)
        error('allowed');
        
    $password = md5(vGET('password'));
    $newpassword = vGET('newpassword');
    $newpassword2 = vGET('newpassword2');
    
    $query = mysql_query('SELECT * FROM `users` WHERE users.uid = "'.$uid.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $member = mysql_fetch_array($query);
    
    if($member['password']!=$password)
        error('own','Das Originalpasswort wurde falsch eingegeben.');

    if($newpassword != $newpassword2)
        error('own','Die Passw&ouml;rter stimmen nicht &uuml;berein.');
        
    if(strlen($newpassword)<5)
        error('own','Die Passwortvorgaben wurden nicht eingehalten.');
    
    if(htmlentities($newpassword)!=$newpassword2)
        error('own','Das Passwort darf keine Sonderzeichen enthalten.');

    mysql_query('UPDATE users SET password="'.md5($newpassword).'" WHERE uid="'.$uid.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    
    write_header('password changed');

    echo 'Das Passwort wurde ge&auml;ndert.<br /><br />
    <a href="'.dire.'ucp/" class="button">&laquo; zur&uuml;ck</a>';
    
    write_footer();

?>
