<?php
$relPath="./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');

require_login();

$title = _("My Character Selectors");

$header_args = [
    "css_files" => [
        "$code_url/styles/manage_char_selector.css",
    ],
    "js_files" => [
        "$code_url/scripts/api.js",
        "$code_url/scripts/my_pickers.js",
    ],
    "js_data" => "
        var apiUrl = '$code_url/api/';
    "
];

output_header($title, NO_STATSBAR, $header_args);
echo "<h1>", $title, "</h1>";
echo "<div class='container'>";
echo "<label for='codes'>", _("Code"), "</label><select id='codes'></select>";
echo "<label for='upper-row'>", _("Upper row"), "</label><input type='text' class='mono-spaced' size='30' id='upper-row' readonly>\n";
echo "<label for='lower-row'>", _("Lower row"), "</label><input type='text' class='mono-spaced' size='30' id='lower-row' readonly>\n";
echo "</div>";
echo "<label for='my-set'>", _("My selectors"), "&nbsp;</label><input type='text' size='60' id='my-set'>\n";
echo "<button id='save-set'>", _("Save"), "</button><br>";
echo "<p>", _("This defines my selectors in order. Codes should be separated by a space."), "</p>";

// vim: sw=4 ts=4 expandtab
