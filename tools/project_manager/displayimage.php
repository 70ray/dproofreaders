<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'page_controls.inc'); // draw_size_controls()

require_login();

// get variables passed into page
$projectid      = validate_projectID('project', @$_GET['project']);
$imagefile      = validate_page_image_filename('imagefile', @$_GET['imagefile'], true);

$title = sprintf(_("Display Image: %s"),$imagefile);

$js_files = [
    "$code_url/tools/mentors/image_size.js",
    "$code_url/tools/project_manager/pageJump.js",
    ];

$header_args = [
    "js_files" => $js_files,
    "js_data" => "var imageUrl = '$projects_url/$projectid/';",
    "body_attributes" => 'class="no-margin"',
];

slim_header($title, $header_args);

echo "<div class='flex_container'>";
echo "<div class='fixedbox control-form'>";

$project = new Project($projectid);

echo "<p>" . html_safe($project->nameofwork) . "&nbsp;<a href='$code_url/project.php?id=$projectid'>" . _("Go to Project Page") . "</a></p>";
draw_size_controls();

draw_page_selector(get_images($projectid), $imagefile);
echo "</div>\n"; // fixedbox

echo "<div class='stretchbox overflow-auto image-back'>\n";
echo "<img id='image'>";
echo "</div>\n"; // stretchbox
echo "</div>\n"; // flex_container

// vim: sw=4 ts=4 expandtab
