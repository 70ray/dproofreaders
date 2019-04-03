<?php

// Create the 'project_pickers' table (initially empty).

$relPath='../../../pinc/';
include_once($relPath.'base.inc');

header('Content-type: text/plain');

echo "Creating project pickers table...\n";
$sql = "
    CREATE TABLE project_pickers
    (
        projectid VARCHAR(22) NOT NULL,
        pickerstring VARCHAR(120),

        PRIMARY KEY (projectid)
    ) CHARACTER SET utf8mb4
";
echo "$sql\n";
mysqli_query(DPDatabase::get_connection(), $sql) or die( mysqli_error(DPDatabase::get_connection()) );

echo "\nDone!\n";

// vim: sw=4 ts=4 expandtab
?>
