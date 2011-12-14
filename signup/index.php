<?

    define('dire','../');
    include(dire.'_env/exec.php');

    $dir = vGET('dir');

    write_header('Sign Up');

    ?>
        Bitte w&auml;hle den passenden Account f&uuml;r Dich aus:
        <br /><br />
        <div style="border: 1px solid #EEE; vertical-align: bottom; padding: 5px; margin: auto; width: 200px;">
        <h2 style="text-align: center;"><a href="./register/?id=1">free account</a></h2>
        <hr />
        10 public repositories<br />
        10 public users<br />
        50MB disk space
        </div>
        <br /><hr /><br />
        <div style="border: 1px solid #EEE; vertical-align: bottom; padding: 5px; width: 200px; float: right;">
        <h2 style="text-align: center;"><a href="./register/?id=3">medium account</a><br /><span style="font-size: 10px;">CHF 10.00/Monat</span></h2>
        <hr />
        10 private repositories<br />
        5 private user<br />
        unlimited public repositories<br />
        unlimited public users<br />
        500MB disk space
        </div>
        <div style="border: 1px solid #EEE; vertical-align: bottom; padding: 5px; width: 200px;">
        <h2 style="text-align: center;"><a href="./register/?id=2">small account</a><br /><span style="font-size: 10px;">CHF 6.00/Monat</span></h2>
        <hr />
        5 private repositories<br />
        1 private user<br />
        unlimited public repositories<br />
        unlimited public users<br />
        200MB disk space
        </div>
        <br />
        <div style="border: 1px solid #EEE; vertical-align: bottom; padding: 5px; width: 200px; float: right;">
        <h2 style="text-align: center;"><a href="./register/?id=5">maxi account</a><br /><span style="font-size: 10px;">CHF 79.00/Monat</span></h2>
        <hr />
        unlimited private repositories<br />
        unlimited private user<br />
        unlimited public repositories<br />
        unlimited public users<br />
        25GB disk space
        </div>
        <div style="border: 1px solid #EEE; vertical-align: bottom; padding: 5px; width: 200px;">
        <h2 style="text-align: center;"><a href="./register/?id=4">large account</a><br /><span style="font-size: 10px;">CHF 25.00/Monat</span></h2>
        <hr />
        50 private repositories<br />
        20 private user<br />
        unlimited public repositories<br />
        unlimited public users<br />
        2GB disk space
        </div>
        <br />
    <?

    write_footer();

?>
