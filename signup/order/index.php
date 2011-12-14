<?

    define('dire','../../');
    include(dire.'_env/exec.php');

    $id = vGET('id');

    write_header('Sign Up &raquo; Order');

    ?>
        Bitte gib Deine Daten an:<br /><br />
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="1794942">
        <input type="image" src="https://www.paypal.com/en_US/CH/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="">
        <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>
        <br /><br /><!--
        <div style="border: 1px solid #EEE; vertical-align: bottom; padding: 5px; margin: auto; width: 200px;">
        <h2 style="text-align: center;"><a href="#">free account</a></h2>
        <hr />
        10 public repositories<br />
        10 public users<br />
        50MB disk space
        </div>
        <br /><hr /><br />
        <div style="border: 1px solid #EEE; vertical-align: bottom; padding: 5px; width: 200px; float: right;">
        <h2 style="text-align: center;"><a href="#">medium account</a><br /><span style="font-size: 10px;">CHF 10.00/Monat</span></h2>
        <hr />
        10 private repositories<br />
        5 private user<br />
        unlimited public repositories<br />
        unlimited public users<br />
        500MB disk space
        </div>
        <div style="border: 1px solid #EEE; vertical-align: bottom; padding: 5px; width: 200px;">
        <h2 style="text-align: center;"><a href="#">small account</a><br /><span style="font-size: 10px;">CHF 6.00/Monat</span></h2>
        <hr />
        5 private repositories<br />
        1 private user<br />
        unlimited public repositories<br />
        unlimited public users<br />
        200MB disk space
        </div>
        <br />
        <div style="border: 1px solid #EEE; vertical-align: bottom; padding: 5px; width: 200px; float: right;">
        <h2 style="text-align: center;"><a href="#">maxi account</a><br /><span style="font-size: 10px;">CHF 79.00/Monat</span></h2>
        <hr />
        unlimited private repositories<br />
        unlimited private user<br />
        unlimited public repositories<br />
        unlimited public users<br />
        25GB disk space
        </div>
        <div style="border: 1px solid #EEE; vertical-align: bottom; padding: 5px; width: 200px;">
        <h2 style="text-align: center;"><a href="#">large account</a><br /><span style="font-size: 10px;">CHF 25.00/Monat</span></h2>
        <hr />
        50 private repositories<br />
        20 private user<br />
        unlimited public repositories<br />
        unlimited public users<br />
        2GB disk space
        </div>
        <br />-->
    <?

    write_footer();

?>
