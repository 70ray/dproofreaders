<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc'); // array_get()
include_once($relPath.'slim_header.inc');

//require_login();

$projectid = array_get($_GET, 'projectid', null); // will get validated in AJAX call
$proj_state = array_get($_GET, 'proj_state', null);
$page_state = array_get($_GET, 'page_state', null);
$imagefile  = array_get($_GET, 'imagefile', null);

$header = _("Report Bad Page");
$header_args = [
    "js_files" => [
        "$code_url/scripts/api.js",
        "$code_url/scripts/bad_page_messages.php",
        "$code_url/scripts/report_bad_page.js",
    ],
    "js_data" =>"
        var codeUrl = '$code_url';
        var apiUrl = '$code_url/api/';
        var projectID = '$projectid';
        var projState = '$proj_state';
        var imageID = '$imagefile';
        var pageState = '$page_state';
    "
];

slim_header($header, $header_args);

echo "<h1>$header</h1>";

echo "<h2>"._("Common Fixes for Bad Pages. Try these first!")."</h2>";
echo "<ul>";
echo "<li>"._("First, we need to look at what a bad page really is.  Remember this is proofreading so you may see line breaks after every word.
  A column may seem to have text missing but all you may need to do is look further down in the text, sometimes the columns may not wrap properly.
  There may actually be a portion of the text missing but not all of it.  In these circumstances as well as similiar ones you would want to proofread the page like normal.
  Move the text where it needs to be, type in any missing text, etc...  These would <b>not</b> be bad pages.")."</li>\n";
echo "<li>"._("Sometimes, the image may not show up due to technical problems with your browser.  Depending upon your browser there are many ways to try to reload that image.
  For example, in Internet Explorer you can right click on the image & left click Show Image or Refresh.  This 90% of the time causes the image to then display.
  Again, this would <b>not</b> be a bad page.")."</li>\n";
echo "<li>"._("Occasionally, you may come across a page that has so many mistakes in the optical character recognition (OCR) that you may think it is a bad page that needs to be re-OCRed.
  However, this is what you are there for.
  You may want to copy it into your local word editing program (eg: Microsoft Word, StarOffice, vi, etc.) and make the changes there & copy them back into the editor.")."</li>\n";
echo "<li>".sprintf(_("Lastly, checking out our common solutions thread may also help you with making sure the report is as correct as possible.  Here's a link to it <a %s>here</a>."),
 "href='$forums_url/viewtopic.php?t=1659' target='_new'") ."</li>\n";
echo "<li>"._("If you've made sure that nothing is going wrong with your computer and you still think it is a bad page please let us know by filling out the information below.
  However, if you are at the least bit hesitant that it may not actually be a bad page please do not mark it so & just hit Cancel on the form above.
  Marking pages bad when they really aren't takes time away from the project managers
  so we want to make sure they don't spend their entire time correcting & adding pages back to the project that aren't bad.") . "</li>\n";
echo "</ul>";

echo "<h2>" . _("Submit a Bad Page Report") . "</h2>";
echo "<p><b>" . _("Reason") . ":</b> ";
echo "<select id='badness-reason'></select>";
echo "</p>";

echo "<input type='button' onclick='badPageControl.report();' value='".attr_safe(_("Submit Report and return to the project page"))."'> ";
echo "<input type='button' value='".attr_safe(_("Cancel"))."' onclick='javascript:history.go(-1)'>";

// vim: sw=4 ts=4 expandtab
