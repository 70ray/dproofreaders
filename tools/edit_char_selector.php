<?php
$relPath="./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param()
include_once($relPath.'utf8_utils.inc'); // maybe_utf8_decode()

require_login();

$action = get_enumerated_param($_GET, 'action', null, ['new', 'edit']);
$code = javascript_safe(maybe_utf8_decode(array_get($_GET, 'code', '')));

if(!$action)
{
    die("No action selected");
}
$code_attrib = '';
if($action == 'new')
{
    $title = _("Add a New Character Selector");
}
else
{
    $title = _("Edit Character Selector");
    $code_attrib = " readonly";
}

$header_args = [
    "css_files" => [
        "$code_url/styles/manage_char_selector.css",
    ],
    "js_files" => [
        "$code_url/scripts/api.js",
        "$code_url/scripts/edit_picker.js",
    ],
    "js_data" => "
        var codeUrl = '$code_url/';
        var apiUrl = '$code_url/api/';
        var action = '$action';
        var code = '$code';
        "
];

output_header($title, NO_STATSBAR, $header_args);
echo "<h1>", $title, "</h1>";
echo "<p>", _("A space character can be used to insert a blank key."), "</p>";
echo "<div class='container'>";
echo "<label for='code'>", _("Code"), "</label><input type='text' size='5' id='code'$code_attrib>\n";
echo "<label for='upper-row'>", _("Upper row"), "</label><input type='text' class='mono-spaced' size='30' id='upper-row'>\n";
echo "<label for='lower-row'>", _("Lower row"), "</label><input type='text' class='mono-spaced' size='30' id='lower-row'>\n";
echo "</div>";
echo "<button id='save'>", _("Save"), "</button>";
echo "<button id='cancel'>", _("Cancel"), "</button>";

// vim: sw=4 ts=4 expandtab
