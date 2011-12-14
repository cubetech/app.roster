<?

    define('dire','../../');
    include(dire.'_env/exec.php');

    session_start();

    $id = vGET('id');
    if(!$id)
        error('transfer');

    $error = vGET('error');
   
    $username = '';
    $mail = '';

    $mailclass = '';
    $mailerrormsg = '';
    $userclass = '';
    $usererrormsg = '';
    $passclass = '';
    $passerrormsg = '';

    if($error) {
        $mail = vGET('mail');
        $username = vGET('username');
        $password = vGET('password');
        $password2 = vGET('password2');
        if(@$error['mail']=='error') {
            $mailclass = ' class="error"';
            $mailerrormsg = '<br /><span style="padding-left: 3px;">Diese Mailadresse wird bereits verwendet.</span>';
        } elseif(@$error['mail']=='false') {
            $mailclass = ' class="error"';
            $mailerrormsg = '<br /><span style="padding-left: 3px;">Diese Mailadresse ist ung&uuml;ltig.</span>';
        }
        if(@$error['username']=='error') {
            $userclass = ' class="error"';
            $usererrormsg = '<br /><span style="padding-left: 3px;">Dieser Username wird bereits verwendet.</span>';
        }
        if(@$error['password']=='error') {
            $passclass = ' class="error"';
            $passerrormsg = '<br /><span style="padding-left: 3px;">Die Passw&ouml;rter stimmen nicht &uuml;berein.</span>';
        } elseif(@$error['password']=='false') {
            $passclass = ' class="error"';
            $passerrormsg = '<br /><span style="padding-left: 3px;">Das Passwort enth&auml;lt ung&uuml;ltige Zeichen.</span>';
        } elseif(@$error['password']=='length') {
            $passclass = ' class="error"';
            $passerrormsg = '<br /><span style="padding-left: 3px;">Das Passwort ist zu kurz.</span>';
        }
    }

    write_header('Sign Up &raquo; Register');

    ?>
        Um Dich im System zu registrieren, f&uuml;lle bitte das Formular aus:
        <br /><br />
        <form action="./confirm/" method="post">
        <input type="hidden" name="id" value="<?=$id?>">
        <table style="width: 450px;">
            <tr>
                <td style="width: 150px; text-align: right;">Account:</td>
                <td style="padding-left: 3px;"><b>free account</b></td>
            </tr>
            <tr>
                <td style="width: 150px; text-align: right; vertical-align: top; padding-top: 7px;">Username:</td>
                <td<?=$userclass?>><input type="text" name="username" value="<?=$username?>">
                <?=$usererrormsg?></td>
            </tr>
            <tr>
                <td style="text-align: right; vertical-align: top; padding-top: 7px;">Passwort:</td>
                <td><input type="password" name="password"></td>
            </tr>
            <tr>
                <td style="text-align: right; vertical-align: top; padding-top: 7px;">Passwort wiederholen:</td>
                <td<?=$passclass?>><input type="password" name="password2"><br />
                <font style="font-size: 10px;">Mindestens 8 Zeichen, keine Sonderzeichen</font>
                <?=$passerrormsg?></td>
            </tr>
            <tr><td colspan="2">&nbsp;</td></tr>
            <tr>
                <td style="text-align: right; vertical-align: top; padding-top: 7px;">Mailadresse:</td>
                <td<?=$mailclass?>><input type="text" name="mail" value="<?=$mail?>"><br />
                <font style="font-size: 10px;">Die Mailadresse muss g&uuml;ltig sein</font>
                <?=$mailerrormsg?></td>
            </tr>
            <tr><td colspan="2">&nbsp;</td></tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" value="Registrieren"></td>
            </tr>
        </table>
        </form>
    <?

    write_footer();

?>
