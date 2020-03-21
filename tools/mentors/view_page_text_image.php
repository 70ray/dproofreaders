<?php
$relPath="../../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'slim_header.inc');
include_once($relPath.'prefs_options.inc');
include_once($relPath.'misc.inc'); // array_get(), get_enumerated_param(), attr_safe(), javascript_safe(), html_safe()
include_once($relPath.'page_controls.inc'); // draw_size_controls()

require_login();

$project = $projectid = $page = $is_valid_page = NULL;
$round_id='OCR';
//See if the user input looks valid

$projectid = trim(array_get($_GET,"projectid",""));

$page = trim(array_get($_GET,"page",""));

$expanded_rounds = array_keys($Round_for_round_id_);
array_unshift($expanded_rounds, 'OCR');
$round_id = get_enumerated_param($_GET, 'round_id', 'OCR', $expanded_rounds);

if(isset($_GET["reset"])) {
    $project = $projectid = $page = $is_valid_page = NULL;
    $round_id='OCR';
}

$js_files = [
    "$code_url/scripts/splitControl.js",
    "$code_url/tools/mentors/page_text_image.js",
    "$code_url/tools/mentors/image_size.js",
    "$code_url/tools/mentors/pageChange.js",
    ];

$header_args = [
    "js_files" => $js_files,
    "body_attributes" => 'class="no-margin"',
];

slim_header(_("Image and text for page"), $header_args);

echo "<div class='flex_container'>";
echo "<div class='fixedbox control-form'>";

if($projectid=="")
{
    echo "<p>", _("Select a project"), "</p>";
}
else
{
    try
    {
        $projectid = validate_projectID('projectid', $projectid);
        $project = new Project($projectid);
        $images = get_images($projectid);
        if(!$images)
        {
            throw new Exception(sprintf(_("There are no images in '%s'"), html_safe($project->nameofwork)));
        }
        // See if the requested page (if any) exists in the project table
        if(!$page)
        {
            $page = $images[0];
        }
        elseif(!in_array($page, $images))
        {
            echo "<p class='error'>", sprintf(_("There is no page '%s'"), html_safe($page)), "</p>";
            $page = $images[0];
        }
        echo "<p>", sprintf(_("Viewing %1\$s text for %2\$s in '%3\$s'"), $round_id, $page, html_safe($project->nameofwork)), "</p>";
        $is_valid_page = true;
    }
    catch(Exception $exception)
    {
        echo "<p class='error'>", html_safe($exception->getMessage()), "</p>";
    }
}

echo "<form method='get' action='view_page_text_image.php'>\n";

if(!$is_valid_page)
{
    echo _("Project ID") . ":&nbsp;";
    echo "<input type='text' maxlength='25' name='projectid' size='25' value='" . attr_safe($projectid) . "' required> \n";
    echo "<input type='submit' value='"._("Select Project")."'> &nbsp; &nbsp;";
    echo _("Page") . ":&nbsp;<input type='text' name='page' size='8'> " . _("(optional)") . " &nbsp; &nbsp;\n";
}
else
{
    echo "<input type='hidden' name='projectid' value='" . attr_safe($projectid) . "'>";
    draw_size_controls();
    draw_page_selector($images, $page);
    echo " &nbsp; &nbsp;\n";
}

echo "<select name='round_id'>";
foreach ($expanded_rounds as $round) {
    echo "<option value='$round'";
    if($round_id && $round == $round_id) echo " selected";
    echo ">$round</option>\n";
}
echo "</select>";

if(!$is_valid_page)
    echo " " . _("(optional)");

echo " &nbsp; &nbsp;<input type='submit' value='" . attr_safe(_("View")) . "'>";

if($is_valid_page)
{
    echo " &nbsp; <input type='submit' name='reset' value='" . attr_safe(_("Reset")) . "'>";
}
echo "</form>";
echo "</div>\n"; // fixedbox

echo "<div id='pane_container' class='stretchbox'>\n";

echo "<div class='pane_1 image-back'>\n";
if($is_valid_page)
{
    echo "<img id='image' src='$projects_url/$projectid/$page'>";
}
echo "</div>\n"; // pane_1

echo "<div class='dragbar'></div>";

echo "<div id='text_pane' class='pane_2'>";

//The text div, we show the saved text in a textarea
//with some of the user's preferences from the proofreading interface
if ($is_valid_page) {
    if ($round_id == "OCR") {
        $text_column_name = 'master_text';
    } else {
        $round = get_Round_for_round_id($round_id);
        if ( is_null($round) )
        {
            die("unexpected parameter round_id = '$round_id'");
        }
        $text_column_name = $round->text_column_name;
    }

    $result = mysqli_query(DPDatabase::get_connection(), sprintf("SELECT $text_column_name FROM $projectid WHERE image = '%s'",mysqli_real_escape_string(DPDatabase::get_connection(), $page)));
    $row = mysqli_fetch_assoc($result);
    $data = $row[$text_column_name];

    // Use the font and wrap prefs for the user's default interface layout,
    // since they're more likely to have set those prefs
    if ( $userP['i_layout']==1 ) {
        $line_wrap   = $userP['v_twrap'];
    } else {
        $line_wrap   = $userP['h_twrap'];
    }
    list($font_face, $font_size) = get_user_proofreading_font();

    // Since this page doesn't have a vertical layout version,
    // we'll use their horizontal prefs for textarea size
    $n_cols = $userP['h_tchars'];
    $n_rows = $userP['h_tlines'];

    list( , $font_size, $font_family) = get_user_proofreading_font();
    $font_size_string = '';
    if ( $font_size != '' )
    {
        $font_size_string = "font-size: $font_size;";
    }
    echo "<textarea
        name='text_data'
        id='text_data'
        cols='$n_cols'
        rows='$n_rows'
        style=\"font-family: $font_family; $font_size_string padding-left: 0.25em;\" ";

    if ( !$line_wrap )
    {
        echo "wrap='off' ";
    }

    echo ">\n";
    echo html_safe($data);
    echo "</textarea>";
}

echo "</div>\n"; // pane_2
echo "</div>\n"; // pane_container
echo "</div>\n"; // flex_container

// vim: sw=4 ts=4 expandtab
