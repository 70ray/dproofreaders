<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'Project.inc');

require_login();

// get variables passed into page
$projectid = array_get($_GET, 'project', null); // will get validated in AJAX call
$imagefile = array_get($_GET, 'imagefile', null);
$percent        = get_integer_param($_GET, 'percent', 100, 1, 999);
$showreturnlink = get_integer_param($_GET, 'showreturnlink', 1, 0, 1);

$title = sprintf(_("Display Image: %s"),$imagefile);

$header_args = [
    "css_files" => [
        "$code_url/styles/display_image.css",
    ],
    "js_files" => [
        "$code_url/scripts/api.js",
        "$code_url/scripts/messages.php",
        "$code_url/scripts/display_image.js",
    ],
    "js_data" =>
        "var projectsUrl = '$projects_url/';
        var codeUrl = '$code_url/';
        var apiUrl = '$code_url/api/';
        var projectID = '$projectid';
        var imageFile = '$imagefile';
        var percent = $percent;"
];

slim_header($title, $header_args);

echo "<div class='flex-container'><div class='top-fix'>";

echo _("Resize"), ": <input type='number' min='10' max='999' id='percent' value=$percent>%
<input type='button' onClick='displayControl.setSize();' value=", attr_safe(_("Resize")), ">&nbsp;", _("Jump to"),":
<select id='jumpto' onChange='displayControl.selectImage(this);'></select>
<input type='button' id='prev-button' value='", attr_safe(_("Previous")), "' onClick='displayControl.prevImage();'>\n
<input type='button' id='next-button' value='" . attr_safe(_("Next")) . "' onClick='displayControl.nextImage();'>\n";

if($showreturnlink)
{
    echo " <a id='return-link'></a>";
}
echo "</div>";

echo "<div class='image-div'><img id='scanimage' class='middle-align' src='' alt=''></div>";
echo "</div>";

// vim: sw=4 ts=4 expandtab
