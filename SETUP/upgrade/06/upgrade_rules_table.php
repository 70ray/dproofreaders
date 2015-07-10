<?php

// One-time script to modify rules table

$relPath='../../../pinc/';
include_once($relPath.'connect.inc');
include_once($relPath.'dpsql.inc');
new dbConnect();

// -----------------------------------------------
// Add new columns to rules table to support round- and document-specific
// random rule selection.
// document points to the faq/FILENAME of the document the rule comes from
// anchor points to the HTML anchor within the document.
// Remove doc column as it assumes single-document guidelines.

echo "Adding and dropping columns to/from 'rules' table...\n";
dpsql_query("
    ALTER TABLE rules 
    ADD document VARCHAR(255) AFTER id,
    ADD anchor VARCHAR(255) AFTER document,
    DROP COLUMN doc
") 
or die("Aborting.");

// -----------------------------------------------
// Remove all existing rules

echo "Removing all existing rules...\n";
dpsql_query("
TRUNCATE rules
") 
or die("Aborting.");

echo "\nDone!\n";

?>
