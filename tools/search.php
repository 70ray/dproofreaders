<?php
$relPath="./../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'metarefresh.inc');
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'special_colors.inc');
include_once($relPath.'gradual.inc');
include_once($relPath.'ProjectSearchForm.inc');
include_once($relPath.'ProjectSearchResults.inc');

require_login();

try {
    $show_view = get_enumerated_param($_GET, 'show', 'blank_search_form',
        array('search_form', 'search', 'p_search', 'blank_search_form', 'set_columns', 'config'));
} catch(Exception $e) {
    $show_view = 'blank_search_form';
}

// exits if handled
handle_set_cols($show_view, "PS");

if($show_view == 'blank_search_form')
{
    unset($_SESSION['search_data']);
    $show_view = 'search_form';
}

$header_args = array("js_files" => array("$code_url/tools/dropdown.js"));
output_header(_("Project Search"), NO_STATSBAR, $header_args);
$search_form = new ProjectSearchForm();

handle_config($show_view, "PS");

if ($show_view == 'search_form')
{
    echo "<h1>", _("Search for Projects"), "</h1>";

    // New proofreaders are having a hard time finding stuff because they
    // end up on the Project Search page instead of the starting round page.
    // See if we can't help them out by pointing them to the starting
    // round page.
    $pagesproofed = get_pages_proofed_maybe_simulated();
    if($pagesproofed < 100)
    {
        echo "<div class='callout'>";
        echo "<div class='calloutheader'>";
        echo _("Looking for projects to proofread?");
        echo "</div>";

        echo "<p>" . sprintf(_("If you're looking for projects to proofread, consider using the list on the <a href='%1\$s'>%2\$s</a> round page instead of this search form."), "$code_url/{$ELR_round->relative_url}#{$ELR_round->id}", $ELR_round->id) . "</p>";
        echo "</p>";

        echo "<p><small>";
        echo _("After a period of time, this message will no longer appear.");
        echo "</small></p>";
        echo "</div>";
    }

    $search_form->render();
    exit();
}

// show must be search or p_search
$condition = $search_form->get_condition($show_view);

echo "<h1 id='head'>", _("Search Results"), "</h1>\n";

// use p_search so it will use saved results if needed
$search_results = new ProjectSearchResults('p_search', "PS");
echo "<p><a href='{$_SERVER['PHP_SELF']}?show=blank_search_form'>" . _("Start New Search") . "</a> | " . get_refine_search_link() . " | " . $search_results->get_search_configure_link() . "</p>";

$search_results->render($condition);

//---------------------------------------------------------------------------


// vim: sw=4 ts=4 expandtab
