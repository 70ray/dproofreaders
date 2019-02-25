<?php

// Create the 'configuration_strings' table (initially empty).

$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

echo "Creating configuration_strings table...\n";
$sql = "
    CREATE TABLE configuration_strings
    (
        id VARCHAR(50) NOT NULL,
        value VARCHAR(256),

        PRIMARY KEY (id)
    )
";
echo "$sql\n";
mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>
