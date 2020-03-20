<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'Project.inc');

require_login();

$default_percent = array_get( @$_SESSION["displayimage"], 'percent', 100 );

// get variables passed into page
$projectid      = validate_projectID('project', @$_GET['project']);
$imagefile      = validate_page_image_filename('imagefile', @$_GET['imagefile'], true);
$percent        = get_integer_param($_GET, 'percent', $default_percent, 1, 999);

$width = 10 * $percent;

$_SESSION["displayimage"]["percent"]=$percent;

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
echo "<input type='number' id='percent' name='percent' min='1' max='999' value='$percent'>%\n";
echo "<button type='button' id='resize'>", _("Resize"), "</button>\n";

echo _("Page");
echo "<select name='jumpto' id='page-select'>";
// Populate the options in the popup menu based on the database query
$res = mysqli_query(DPDatabase::get_connection(),  "SELECT image FROM $projectid ORDER BY image ASC") or die(mysqli_error(DPDatabase::get_connection()));
while($row = mysqli_fetch_assoc($res))
{
    $this_val = $row["image"];
    echo "<option value=\"$this_val\"";
    if ($this_val == $imagefile) echo " selected";
    echo ">".$this_val."</option>\n";
}
echo "</select>&nbsp;";

echo "<input type='button' id='prev-button' value='" . attr_safe(_("Previous")) . "'>\n";
echo "<input type='button' id='next-button' value='" . attr_safe(_("Next")) . "'>\n";

echo "</div>\n"; // fixedbox

echo "<div class='stretchbox overflow-auto image-back'>\n";
echo "<img id='image' width='width'>";
echo "</div>\n"; // stretchbox
echo "</div>\n"; // flex_container

// vim: sw=4 ts=4 expandtab
