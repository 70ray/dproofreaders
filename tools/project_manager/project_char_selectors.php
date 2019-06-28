<?php
$relPath="../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'selector_common.inc');

require_login();

$projectid      = validate_projectID('projectid', @$_GET['projectid']);
$project = new Project($projectid);
if(!$project->can_be_managed_by_user($pguser))
{
    die('You are not authorized to invoke this script.');
}

$title = sprintf(_("Character Selectors for %s"), $project->nameofwork);

$header_args = [
    "css_files" => [
        "$code_url/styles/manage_char_selector.css",
    ],
    "js_files" => [
        "$code_url/scripts/api.js",
        "$code_url/scripts/pickers_common.js",
        "$code_url/scripts/project_pickers.js",
    ],
    "js_data" => "
        var apiUrl = '$code_url/api/';
        var projectID = '$project->projectid';
    "
];

output_header($title, NO_STATSBAR, $header_args);
echo "<h1>", $title, "</h1>";
draw_selectors();
echo "<br>";
draw_selector_set(_("Extra  Selectors"));

// vim: sw=4 ts=4 expandtab
