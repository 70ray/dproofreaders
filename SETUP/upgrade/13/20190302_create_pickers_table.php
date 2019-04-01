<?php

// Create the 'character_pickers' table (initially empty).

$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

echo "Creating character pickers table...\n";
$sql = "
    CREATE TABLE character_pickers
    (
        code  VARCHAR(20) NOT NULL,
        upper VARCHAR(120),
        lower VARCHAR(120),

        PRIMARY KEY (code)
    ) CHARACTER SET utf8mb4
";
echo "$sql\n";
mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>
