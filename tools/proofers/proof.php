<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'proof_components.inc');
include_once($relPath.'ProofreadingToolbox.inc');

require_login();

// (User clicked on "Start Proofreading" link or
// one of the links in "Done" or "In Progress" trays.)

$projectid = array_get($_GET, 'projectid', null); // will get validated in AJAX call
$proj_state = array_get($_GET, 'proj_state', null);
$page_state = array_get($_GET, 'page_state', null);
$imagefile  = array_get($_GET, 'imagefile', null);

$header_args = [
    "css_files" => [
        "$code_url/styles/proof.css",
        "$code_url/styles/split_view.css",
        "$code_url/styles/toolbox.css",
    ],
    "js_files" => [
        "$code_url/scripts/api.js",
        "$code_url/scripts/messages.php",
        "$code_url/scripts/splitControl.js",
        "$code_url/scripts/proof.js",
        "$code_url/scripts/text_tools.js",
        "$code_url/scripts/character_selector.js",
    ],
    "js_data" =>
        "var projectsUrl = '$projects_url/';
        var codeUrl = '$code_url/';
        var apiUrl = '$code_url/api/';
        var projectID = '$projectid';
        var projState = '$proj_state';
        var imageID = '$imagefile';
        var pageState = '$page_state';"
];

slim_header(_("Proofreading Interface"), $header_args);

echo "<div id='topbar'></div>";
echo "<div id='pane_1'><div class='center-align' id='imagedisplay'><img id='scanimage' class='middle-align' src='' alt=''></div></div>";
echo "<div id='dragbar'></div>";
echo "<div id='pane_2'><div id = 'proofdiv' class='center-align'><textarea id='text_area'></textarea></div></div>";
echo "<div id='botbar'><div class='control-div'>";
echo_controls();
$toolbox = new ProofreadingToolbox();
$toolbox->render();
echo "</div></div>";
