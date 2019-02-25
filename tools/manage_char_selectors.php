<?php
$relPath="./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

$title = _("Manage Character Selectors");
$confirm_delete = _("Are you sure you want to remove '%s'?");

$header_args = [
    "css_files" => [
        "$code_url/styles/manage_char_selector.css",
    ],
    "js_files" => [
        "$code_url/scripts/api.js",
        "$code_url/scripts/misc.js",
        "$code_url/scripts/manage_pickers.js",
    ],
    "js_data" => "
        var codeUrl = '$code_url/';
        var apiUrl = '$code_url/api/';
        var confirmDelete = \"$confirm_delete\";
    "
];

output_header($title, NO_STATSBAR, $header_args);
echo "<h1>", $title, "</h1>";
echo "<select id='codes'></select>";
echo "<button id='edit-button'>", _("Edit"), "</button>";
echo "<button id='delete-button'>", _("Delete"), "</button><br>";
echo "<button id='new-button'>", _("Add a new Character Selector"), "</button><br>";
echo "<label for='def-set'>", _("Default selectors"), "&nbsp;</label><input type='text' size='60' id='def-set'>\n";
echo "<button id='save-set'>", _("Save"), "</button><br>";
echo "<p>", _("This defines the default selectors in order. Codes should be separated by a space. The first one will be active initially"), "</p>";

// vim: sw=4 ts=4 expandtab
