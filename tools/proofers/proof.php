<?php
$relPath="./../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'slim_header.inc');

require_login();

// (User clicked on "Start Proofreading" link or
// one of the links in "Done" or "In Progress" trays.)

/* $_GET from IN PROGRESS/DONE and from 'Edit' links on Images,Diffs screen
url_for_pi_do_particular_page()
$projectid, $proj_state, $imagefile, $page_state
*/

/* $_GET from "Start Proofreading" etc.
url_for_pi_do_whichever_page()
$projectid, $proj_state
*/

$projectid = array_get($_GET, 'projectid', null); // will get validated in AJAX call
$proj_state = array_get($_GET, 'proj_state', null);
$page_state = array_get($_GET, 'page_state', null);
$imagefile  = array_get($_GET, 'imagefile', null);

$header_args = [
    "css_files" => [
        "$code_url/styles/proof.css",
    ],
    "js_files" => [
        "$code_url/scripts/api.js",
        "$code_url/scripts/messages.php",
        "$code_url/scripts/proof.js",
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

echo "<div class='flex-container'>";
echo "<div class='image-div'><img id='scanimage' class='middle-align' src='' alt=''></div>";
echo "<div class='text-div'><textarea id='text_area'></textarea></div>";
echo "<div class='control-div'>";
echo "<button type='button' onClick='proofControl.stop();'>Stop Proofreading</button>";
echo "<button type='button' onClick='proofControl.returnToRound();'>Return Page to round</button>";
echo "</div>";
echo "</div>";


/*

if (empty($expected_state)) die( "parameter 'proj_state' is empty" );

$project = new Project($projectid);

// Check $expected_state.
$expected_state_text = project_states_text($expected_state);
if (empty($expected_state_text)) die( "parameter 'proj_state' has bad value: '$expected_state'" );
if ($expected_state != $project->state)
{
    slim_header( $project->nameofwork );

    echo "<p>";
    echo sprintf(
         ('Warning: Project "%1$s" is no longer in state "%2$s"; it is now in state "%3$s".'),
        $project->nameofwork,
        $expected_state_text,
        project_states_text($project->state)
    );
    echo "</p>\n";

    $expected_round = get_Round_for_project_state($expected_state);
    echo "<p>";
    echo sprintf(
        _('Back to <a href="%1$s">%2$s</a>'),
        "$code_url/tools/proofers/round.php?round_id={$expected_round->id}",
        $expected_round->name
    );
    echo "</p>\n";

    exit;
}

// Check that the project is in a proofable state
list($code,$msg) = $project->can_be_proofed_by_current_user();
if ( $code != $project->CBP_OKAY )
{
    // I think this can only happen via URL-tweaking.

    slim_header( $project->nameofwork );

    echo _("Project") . ": \"{$project->nameofwork}\"<br>\n";
    echo pgettext("project state", "State")   . ": " . project_states_text($project->state) . "<br>\n";
    echo "<p>$msg</p>\n";

    echo "<p>";
    echo sprintf(
        _("Back to <a href='%s'>Activity Hub</a>"),
        "$code_url/activity_hub.php"
    );
    echo "</p>\n";

    exit;
}

//load the master frameset

// Add name of round before nameofwork
$round = get_Round_for_project_state($project->state);
$nameofwork = "[" . $round->id . "] " . $project->nameofwork;

$header_args = array(
    "js_files" => array(
        "$code_url/tools/proofers/dp_proof.js",
        "$code_url/tools/proofers/dp_scroll.js",
    )
);
slim_header_frameset($nameofwork." - "._("Proofreading Interface"), $header_args);

$frameGet="?" . $_SERVER['QUERY_STRING'];
?>
<frameset id="proof_frames" rows="*,73">
<frame name="proofframe" src="<?php echo "$code_url/tools/proofers/proof_frame.php{$frameGet}";?>" marginwidth="2" marginheight="2" frameborder="0">
<frame name="menuframe" src="ctrl_frame.php?round_id=<?php echo $round->id; ?>" marginwidth="2" marginheight="2" frameborder="0">
</frameset>
<noframes>
<?php echo _("Your browser currently does not display frames!"); ?>
</noframes>
*/