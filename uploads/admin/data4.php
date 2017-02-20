<?php
putenv("PGPASSWORD=Procesamiento617");
$dumpcmd = array("pg_dump", "-h", escapeshellarg("10.1.0.114"), "-U", escapeshellarg("postgres"), "-W", "-f", escapeshellarg("/var/www/ipme/download/backup4.sql"), escapeshellarg("db_ipm"));
exec( join(' ', $dumpcmd), $cmdout, $cmdresult );
putenv("PGPASSWORD");
if ($cmdresult != 0)
{
    # Handle error here...
    echo "error";
}
