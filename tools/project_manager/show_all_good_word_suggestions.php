<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'wordcheck_engine.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'misc.inc'); // attr_safe()
include_once($relPath.'Stopwatch.inc');
include_once($relPath.'misc.inc'); // array_get(), get_integer_param(), surround_and_join()
include_once('./post_files.inc');
include_once("./word_freq_table.inc");

require_login();

$submitLabel = _("Add selected words to Good Words List");
$header_args = [
    "css_files" => [
        "$code_url/styles/split_view.css",
        "$code_url/styles/fix_head.css",
    ],
    "js_files" => [
        "$code_url/scripts/api.js",
        "$code_url/scripts/splitControl.js",
        "$code_url/scripts/good_words.js",
        "$code_url/scripts/good_words_messages.php",
    ],
    "js_data" => "
        var apiUrl = '$code_url/api/';
        var pgUser = '$pguser';
    "
];

slim_header(_("Manage Suggestions"), $header_args);

echo "<div id='topbar'></div>";
echo "<div id='pane_1'>";

echo "<div class='flex-container'><div class='top-fix'>";

echo "<h1>" . _("Manage Suggestions") . "</h1>";

// TRANSLATORS: PM = project manager
echo "<a href='$code_url/tools/project_manager/projectmgr.php' target='_TOP'>" . _("Return to the PM page") . "</a><br>";

echo "<div id='input-user' class='hidden'>", _("View projects for user:"), "&nbsp;<input type='text' id='pm_name' size='10'></div>";

$timeCutoffOptions=array(1,2,3,4,5,6,7,14,21);
// initial value timeCutoff is -1;
echo _("Show") . ": ";
echo "<select id='time-cutoff'>";
echo "<option value='0'>" , _("All suggestions") , "</option>\n";
echo "<option value='-1' selected>" , _("Suggestions since Good Words List was saved") , "</option>\n";

// use the days as value since page could become stale
foreach($timeCutoffOptions as $timeCutoffOption)
{
    echo "<option value='$timeCutoffOption'>", sprintf(_("Suggestions made in the past %d days"), $timeCutoffOption), "</option>\n";
}
echo "</select>";
echo "<br>";

echo "<input type='button' onclick='sagws.show();' value='", attr_safe(_("Go")), "'>";

echo "<p class='warning'>" . sprintf(_("Selecting a '%s' button will add selected words to the corresponding project word list."), $submitLabel) . "</p>\n";

$freqCutoffOptions = array(1,2,3,4,5,10,25,50);
$defaultFreqCutoff = 5;

echo _("Cutoff frequency ");
echo "<select id='freq_select' onchange='sagws.setFreqCutoff(\"\");'>\n";
foreach($freqCutoffOptions as $freqCutoffOption)
{
    $selected = ($defaultFreqCutoff == $freqCutoffOption) ? " selected" : "";
    echo "<option value='$freqCutoffOption'$selected>", $freqCutoffOption, "</option>\n";
}
echo "</select>\n";
echo _(" Words that appear fewer times are not shown");
echo "<br>";
echo "</div>"; // top-fix
echo "<p id='no-suggestions' class='hidden'>" . _("No projects have proofreader suggestions for the given timeframe.") . "</p>";
echo "<div id='project_data' class='content'></div>";
echo "</div>\n"; // flex-container
echo "</div>"; // pane_1
echo "<div id='dragbar'></div>";
echo "<div id='pane_2'><iframe name='detailframe'></iframe></div>";
echo "<div id='botbar'></div>";

// omitted actual cutoff time statement - should get it from server
// omitted time to generate data
// vim: sw=4 ts=4 expandtab
