<?php
$relPath="./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'selector_common.inc');

require_login();
if(!user_is_PM())
{
    die('permission denied');
}

$title = _("Manage Character Selectors");
$confirm_delete = _("Are you sure you want to remove '%s'?");

$header_args = [
    "css_files" => [
        "$code_url/styles/manage_char_selector.css",
    ],
    "js_files" => [
        "$code_url/scripts/api.js",
        "$code_url/scripts/misc.js",
        "$code_url/scripts/pickers_common.js",
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
draw_selectors();
echo "<button id='edit-button'>", _("Edit"), "</button>";
echo "<button id='delete-button'>", _("Delete"), "</button><br>";
echo "<button id='new-button'>", _("Add a new Character Selector"), "</button><br>";
draw_selector_set(_("Default selectors"));

// vim: sw=4 ts=4 expandtab
