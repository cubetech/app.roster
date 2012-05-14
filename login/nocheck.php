<?
  define('dire','../');
  include(dire.'_env/exec.php');

  authed();
  $uid = authed();

  write_header('center','Eingeloggt');
?>
Du hast Dich erfolgreich eingeloggt!<br><br>
<font color=red><b>Achtung:</b></font><br>Du hast Deinem Konto noch nicht den Status "Vertrauensw&uuml;rdig" verliehen.<br>
Dies wird jedoch sehr empfohlen, damit andere Benutzer in Deinem Profil sehen k&ouml;nnen, dass Dein Konto nicht gef&auml;lscht ist.<br>
<br>Um Dein Konto zu &uuml;berpr&uuml;fen sende per SMS<br><b>FP CHECK <?php print $uid?></b><br>an die Nummer 939 (CHF 0.20/SMS).<br><br>
Mit den besten Gr&uuml;ssen<br>
Dein fire-pics.ch Team<br><br>
<?php print design_createButton('','&laquo; Startseite',dire)?>
<?
  write_footer();
?>