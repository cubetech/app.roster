<?

    define('dire','../../../');
    include(dire.'_env/exec.php');

    write_header('create new repository');

    echo '  <form action="./save/" method="post">
            <table>
                <tr>
                    <td class="tr">Name:</td>
                    <td><input type="text" name="name"><br />
                        <small>Der Name sollte aus folgenden Zeichen bestehen:<br />
                        a bis z, A bis Z, 0 bis 9, -_.</td>
                </tr>
                <tr>
                    <td class="tr" style="padding-top: 2px;"><input type="checkbox" name="private"></td>
                    <td>Dieses Repository auf "privat" setzen</td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input type="submit" value="create"></td>
                </tr>
            </table>
            </form>
    ';

    write_footer();

?>
