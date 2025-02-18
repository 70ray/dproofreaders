<?php
$relPath = '../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'User.inc');
include_once($relPath.'js_newwin.inc'); // get_js_for_links_to_project_pages(),  get_onclick_attr_for_link_to_project_page()
include_once($relPath.'Settings.inc');
include_once($relPath.'pg.inc'); // get_pg_catalog_link_for_etext()
include_once($relPath.'gradual.inc'); // maybe_output_new_proofer_message()

require_login();

$username = $pguser;
if (user_is_a_sitemanager() || user_is_proj_facilitator()) {
    $username = $_GET['username'] ?? $pguser;
    if (!User::is_valid_user($username)) {
        die("Invalid username.");
    }
}
$focus_user = new User($username);

[$round_view_options, $pool_view_options] = get_view_options($username);
[$round_column_specs, $pool_column_specs] = get_table_column_specs();
$round_sort_options = get_sort_options($round_column_specs);
$pool_sort_options = get_sort_options($pool_column_specs);

// Get changes to the round and pool views and sorting. If not set, we
// pull the last selected option from UserSettings.
$userSettings = & Settings::get_Settings($pguser);
$round_view = get_enumerated_param(
    $_GET,
    "round_view",
    $userSettings->get_value("my_projects:round_view", "recent"),
    array_keys($round_view_options)
);
$pool_view = get_enumerated_param(
    $_GET,
    "pool_view",
    $userSettings->get_value("my_projects:pool_view", "reserved"),
    array_keys($pool_view_options)
);
$round_sort = get_enumerated_param(
    $_GET,
    'round_sort',
    $userSettings->get_value("my_projects:round_sort", "timeD"),
    $round_sort_options
);
$pool_sort = get_enumerated_param(
    $_GET,
    'pool_sort',
    $userSettings->get_value("my_projects:pool_sort", "titleA"),
    $pool_sort_options
);

// Update saved view and sort settings if they've changed
foreach (["round_view", "pool_view", "round_sort", "pool_sort"] as $setting) {
    if ($$setting != $userSettings->get_value("my_projects:$setting")) {
        $userSettings->set_value("my_projects:$setting", $$setting);
    }
}

$page_header = [
    "text_self" => _("My Projects"),
    "text_other" => sprintf(_("%s's Projects"), $username),
];

$extra_args['js_data'] = get_js_for_links_to_project_pages();

output_header(get_usertext($page_header), NO_STATSBAR, $extra_args);

output_link_box($username);

echo "<h1>" . get_usertext($page_header) . "</h1>";

$allowed_stages = array_keys(get_stages_user_can_work_in($username));
$can_view_post_processing = in_array("PP", $allowed_stages) or in_array("PPV", $allowed_stages);
$proof_heading = _("Proofreading & Formatting Projects");
$pool_heading = _("Post-Processing Projects");
if ($can_view_post_processing) {
    echo "<ul class='quick-links'>";
    echo "<li><a href='#round_view'>{$proof_heading}</a></li>";
    echo "<li><a href='#pool_view'>{$pool_heading}</a></li>";
    echo "</ul>";
}

maybe_output_new_proofer_message();

// --------------------------------------------------------------------------
// Round table

// prep an array of available states
$avail_states = [];
foreach (Rounds::get_all() as $round) {
    $avail_states[] = $round->project_available_state;
}

echo "<h2 id='round_view'>" . html_safe($proof_heading) . "</h2>";

show_page_menu($round_view_options, $round_view, $username, 'round_view');

[$res, $colspecs] = get_round_query_result($round_view, $round_sort, $round_column_specs, $username);
if (mysqli_num_rows($res) == 0) {
    echo "<p>" . $round_view_options[$round_view]["text_none"] . "</p>";
} else {
    echo "<p>" . get_usertext($round_view_options[$round_view]) . "</p>";

    echo "<table class='themed theme_striped' style='width: auto;'>";

    show_headings($colspecs, $round_sort, $username, 'round_sort', 'round_view');

    $n_rows_displayed = 0;
    while ($row = mysqli_fetch_object($res)) {
        if ($row->state == PROJ_DELETE && $round_view != "bookmark") {
            // it's been deleted. see if it's been merged into another one.
            if (str_contains($row->deletion_reason, 'merged') &&
                (1 == preg_match(
                    '/\b(projectID[0-9a-f]{13})\b/',
                    $row->deletion_reason,
                    $matches
                ))) {
                // get the dope from the project it was merged into
                $project = new Project($matches[1]);
                if ($project->archived) {
                    // The project it was merged into has been archived.
                    // So skip it.
                    continue;
                }
                $projectid = $matches[0];
                $state = $project->state;
                $nameofwork = $project->nameofwork;
                $orig_nameofwork = $row->nameofwork;
                $days_checkedout = (time() - $project->modifieddate) / (60 * 60 * 24);
            } else {
                // deleted but not merged. We are not interested.
                continue;
            }
        } else {
            // nothing special. Just the straight dope.
            $projectid = $row->projectid;
            $state = $row->state;
            $nameofwork = $row->nameofwork;
            $orig_nameofwork = '';
            $n_available_pages = $row->n_available_pages;
            $percent_done = $row->percent_done;
            $days_checkedout = $row->days_checkedout;
        }

        // for the Available tab, confirm that the user can actually work in
        // them accounting for the project reserve.
        if ($round_view == "available") {
            $project = new Project($projectid);
            try {
                $round = Rounds::get_by_project_state($project->state);
                validate_user_against_project_reserve($focus_user, $project, $round);
            } catch (Exception $exception) {
                continue;
            }
        }

        echo "<tr>\n";

        echo "<td>";
        if ($orig_nameofwork != '') {
            // say where this information came from
            echo html_safe($orig_nameofwork) . " <i>" .  _("merged into") . "</i> ";
        }
        $url = "$code_url/project.php?id=$projectid";
        $onclick_attr = get_onclick_attr_for_link_to_project_page($url);
        echo "<a href='$url' $onclick_attr>" . html_safe($nameofwork) . "</a>";
        echo "</td>\n";

        if (isset($colspecs['state'])) {
            echo "<td class='nowrap'>";
            echo get_medium_label_for_project_state($state);
            if ($state == PROJ_POST_FIRST_CHECKED_OUT) {
                $project = new Project($projectid);
                if ($project->is_available_for_smoothreading()) {
                    echo " + SR";
                }
            }
            echo "</td>\n";
        }

        if (isset($colspecs['time'])) {
            echo "<td class='nowrap'>";
            if ($row->max_timestamp > 0) {
                echo date('Y-m-d H:i', $row->max_timestamp);
            }
            echo "</td>\n";
        }

        if (isset($colspecs['n_available_pages']) && isset($colspecs['percent_done'])) {
            // Don't show these fields for merged projects
            if (in_array($state, $avail_states) && $orig_nameofwork == '') {
                echo "<td class='right-align'>";
                echo $n_available_pages;
                echo "</td>\n";

                echo "<td class='right-align'>";
                echo sprintf("%d%%", $percent_done * 100);
                echo "</td>\n";
            } else {
                echo "<td></td><td></td>";
            }
        }

        if (isset($colspecs['days_checkedout'])) {
            echo "<td class='right-align'>";
            echo sprintf("%0.1f", $days_checkedout);
            echo "</td>\n";
        }

        if (isset($colspecs['postednum'])) {
            echo "<td class='right-align'>";
            echo get_pg_catalog_link_for_etext($row->postednum, $row->postednum);
            echo "</td>\n";
        }

        echo "</tr>\n";

        $n_rows_displayed++;
    }

    echo "</table>\n";

    echo sprintf(_("(%d projects)"), $n_rows_displayed);
    echo "<br>\n";
}

// --------------------------------------------------------------------------
// Pool table

// don't show PP/PPV if the user isn't allowed to work in it
if (!$can_view_post_processing) {
    exit;
}

echo "<h2 id='pool_view'>" . html_safe($pool_heading) . "</h2>\n";

show_page_menu($pool_view_options, $pool_view, $username, 'pool_view');

[$res, $colspecs, $pool_sort] = get_pool_query_result($pool_view, $pool_sort, $pool_column_specs, $username);
$num_projects = mysqli_num_rows($res);
if ($num_projects == 0) {
    echo "<p>" . $pool_view_options[$pool_view]["text_none"] . "</p>";
} else {
    echo "<p>" . get_usertext($pool_view_options[$pool_view]) . "</p>";

    echo "<table class='themed theme_striped' style='width: auto;'>";

    show_headings($colspecs, $pool_sort, $username, 'pool_sort', 'pool_view');

    $pool_checkedout_states = [
        PROJ_POST_FIRST_CHECKED_OUT,
        PROJ_POST_SECOND_CHECKED_OUT,
    ];

    while ($row = mysqli_fetch_assoc($res)) {
        $project = new Project($row);

        echo "<tr>\n";

        echo "<td>";
        echo "<a href='$code_url/project.php?id=$project->projectid'>" . html_safe($project->nameofwork) . "</a>";
        echo "</td>\n";

        echo "<td>";
        echo $project->username;
        echo "</td>\n";

        if (isset($colspecs['postproofer'])) {
            echo "<td>";
            echo $project->PPer;
            echo "</td>\n";
        }

        if (isset($colspecs['ppverifier'])) {
            echo "<td>";
            echo $project->PPVer;
            echo "</td>\n";
        }

        if (isset($colspecs['state'])) {
            echo "<td class='nowrap'>";
            echo get_medium_label_for_project_state($project->state);
            if ($project->state == PROJ_POST_FIRST_CHECKED_OUT) {
                if ($project->is_available_for_smoothreading()) {
                    echo " + SR";
                }
            }
            echo "</td>\n";
        }

        if (isset($colspecs['checkedoutby'])) {
            echo "<td>";
            echo $project->checkedoutby;
            echo "</td>\n";
        }

        if (isset($colspecs["days_checkedout"])) {
            echo "<td class='right-align'>";
            if (in_array($project->state, $pool_checkedout_states)) {
                echo sprintf("%0.1f", $project->days_checkedout);
            }
            echo "</td>\n";
        }

        if (isset($colspecs['postednum'])) {
            echo "<td class='right-align'>";
            echo get_pg_catalog_link_for_etext($project->postednum, $project->postednum);
            echo "</td>\n";
        }

        echo "</tr>\n";
    }

    echo "</table>\n";

    echo sprintf("(%d projects)", $num_projects);
    echo "<br>\n";
}

// --------------------------------------------------------------------------

function output_link_box($username)
{
    echo "<div id='linkbox'>";
    if (user_is_a_sitemanager() || user_is_proj_facilitator()) {
        echo "<form action='#' method='get'><p>";
        echo _("See projects for another user") . "<br>";
        echo "<input type='text' name='username' value='" . attr_safe($username) . "' autocapitalize='none' required>";
        echo "<input type='submit' value='" . attr_safe(_("Refresh")) . "'>";
        echo "</p></form>\n";
        echo "<hr>";
    }
    $links = [
        "my_suggestions.php" => _("My Suggestions"),
        "review_work.php" => _("Review Work"),
    ];
    echo "<h2>" . _("Links") . "</h2>";
    echo "<ul>";
    foreach ($links as $url => $text) {
        echo "<li><a href='$url'>$text</a></li>";
    }
    echo "</ul>";
    echo "</div>";
}

// --------------------------------------------------------------------------

function get_table_column_specs()
{
    // $colspecs = array (
    //     $id => array ( 'label' => $label, 'sql' => $sql )
    // );
    // $id is the column name as passed by GET argument.
    // $label is the translatable label displayed in the column header
    // $sql is the SQL collator expression, or NULL for unsortable columns.
    // $class is the HTML class to use for the field on output
    $round_columns = [
        'title' => [
            'label' => _('Title'),
            'sql' => 'projects.nameofwork',
        ],
        'state' => [
            'label' => _('Current State'),
            'sql' => sql_collator_for_project_state('projects.state'),
        ],
        'time' => [
            'label' => _('Time of Last Activity'),
            'sql' => 'max_timestamp',
        ],
        'n_available_pages' => [
            'label' => _('Available<br>Pages'),
            'sql' => 'n_available_pages',
            'class' => 'right-align',
        ],
        'percent_done' => [
            'label' => _('Done'),
            'sql' => 'percent_done',
            'class' => 'right-align',
        ],
        'days_checkedout' => [
            'label' => _('Days in State'),
            'sql' => 'days_checkedout',
            'class' => 'right-align',
        ],
        'postednum' => [
            'label' => _('eBook'),
            'sql' => 'postednum',
            'class' => 'right-align',
        ],
    ];

    $pool_columns = [
        'title' => [
            'label' => _('Title'),
            'sql' => 'nameofwork',
        ],
        'manager' => [
            'label' => _('Project Manager'),
            'sql' => 'username',
        ],
        'postproofer' => [
            'label' => _("PPer"),
            'sql' => 'postproofer',
        ],
        'ppverifier' => [
            'label' => _("PPVer"),
            'sql' => 'ppverifier',
        ],
        'state' => [
            'label' => _('Current State'),
            'sql' => sql_collator_for_project_state('state'),
        ],
        'checkedoutby' => [
            'label' => _("Checked Out By"),
            'sql' => 'checkedoutby',
        ],
        'days_checkedout' => [
            'label' => _('Days Checked Out'),
            'sql' => 'days_checkedout',
            'class' => 'right-align',
        ],
        'postednum' => [
            'label' => _('eBook'),
            'sql' => 'postednum',
            'class' => 'right-align',
        ],
    ];

    return [$round_columns, $pool_columns];
}

function get_sort_options($colspecs)
{
    $sort_options = [];
    $columns = array_keys($colspecs);
    foreach ($columns as $column) {
        $sort_options[] = "{$column}A";
        $sort_options[] = "{$column}D";
    }
    return $sort_options;
}

// to make sure that some projects are displayed, iterate over the view order
function get_view_options($username)
{
    $round_view_options = [
        "available" => [
            "label" => _("Available"),
            "text_self" => _("All projects in which you have proofread or formatted a page that are currently available for you to work in again."),
            "text_other" => sprintf(_("All projects in which %s has proofread or formatted a page that are currently available for them to work in again."), $username),
            "text_none" => _("No previously proofread or formatted projects are currently available."),
        ],
        "recent" => [
            "label" => _("Recent"),
            "text_self" => _("Projects in which you have proofread or formatted a page in the past 100 days."),
            "text_other" => sprintf(_("Projects in which %s proofread or formatted a page in the past 100 days."), $username),
            "text_none" => _("No recent projects found."),
        ],
        "active" => [
            "label" => _("Active"),
            "text_self" => _("All projects that are not yet posted in which you have proofread or formatted a page."),
            "text_other" => sprintf(_("All projects that are not yet posted in which %s has proofread or formatted a page."), $username),
            "text_none" => _("No projects found that have not yet been posted."),
        ],
        "bookmark" => [
            "label" => _("Bookmarks"),
            "text_self" => _("Projects you've bookmarked on the project's page."),
            "text_other" => sprintf(_("Projects that %s bookmarked on the project's page."), $username),
            "text_none" => _("No projects bookmarked."),
        ],
        "posted" => [
            "label" => _("Posted to PG"),
            "text_self" => _("All projects that have been posted to Project Gutenberg in which you have proofread or formatted a page."),
            "text_other" => sprintf(_("All projects that have been posted to Project Gutenberg in which %s has proofread or formatted a page."), $username),
            "text_none" => _("No projects found that have been posted to Project Gutenberg."),
        ],
    ];

    $pool_view_options = [
        "reserved" => [
            "label" => _("Reserved"),
            "text_self" => _("Projects reserved for you to post-process."),
            "text_other" => sprintf(_("Projects reserved for %s to post-process."), $username),
            "text_none" => _("No projects reserved for post-processing."),
        ],
        "active" => [
            "label" => _("Active"),
            "text_self" => _("Projects you checked out for Post-Processing or for Post-Processing Verification. Projects checked out for Post-Processing may be in PP, available for PPV, or in PPV."),
            "text_other" => sprintf(_("Projects %s checked out for Post-Processing or for Post-Processing Verification. Projects checked out for Post-Processing may be in PP, available for PPV, or in PPV."), $username),
            "text_none" => _("No projects found."),
        ],
        "posted" => [
            "label" => _("Posted to PG"),
            "text_self" => _("All projects that are posted to Project Gutenberg for which you are credited as Post-Processor or Post-Processing Verifier."),
            "text_other" => sprintf(_("All projects that are posted to Project Gutenberg for which %s is credited as Post-Processor or Post-Processing Verifier."), $username),
            "text_none" => _("No projects found."),
        ],
    ];

    return [$round_view_options, $pool_view_options];
}

function get_usertext($text_options)
{
    global $pguser, $username;

    if ($pguser == $username) {
        return $text_options["text_self"];
    } else {
        return $text_options["text_other"];
    }
}


function show_page_menu($all_view_modes, $round_view, $username, $key)
{
    global $pguser;

    $qs_username = "";
    if ($pguser != $username) {
        $qs_username = "username=$username";
    }

    output_tab_bar($all_view_modes, $round_view, $key, "$qs_username#$key");
}

function sql_order_spec($colspecs, $order_col, $order_dir)
{
    return
        $colspecs[$order_col]['sql']
        . ' '
        . ($order_dir == 'A' ? 'ASC' : 'DESC');
}

function get_sort_col_and_dir($sort)
{
    // The sort string is already a valid one when we get here, we just need
    // to parse the column and direction apart.
    $order_dir = substr($sort, strlen($sort) - 1, 1);
    $order_col = substr($sort, 0, strlen($sort) - 1);
    return [$order_col, $order_dir];
}

function show_headings($colspecs, $sorting, $username, $sort_name, $anchor)
{
    global $pguser;

    [$order_col, $order_dir] = get_sort_col_and_dir($sorting);

    echo "<tr>\n";
    foreach ($colspecs as $col_id => $colspec) {
        if ($col_id == $order_col) {
            // This is the column on which the table is being sorted.
            // If the user clicks on this column-header, the result should be
            // the table, sorted on this column, but in the opposite direction.
            $link_dir = ($order_dir == 'A' ? 'D' : 'A');
            $caret = $link_dir == 'D' ? "&nbsp;&#9650;" : "&nbsp;&#9660;";
        } else {
            // This is not the column on which the table is being sorted.
            // If the user clicks on this column-header, the result should be
            // the table, sorted on this column, in ascending order.
            $link_dir = 'A';
            $caret = '';
        }
        $class = '';
        if (isset($colspec['class'])) {
            $class = sprintf("class='%s'", $colspec['class']);
        }
        echo "<th $class>";
        $qs_username = "";
        if ($username != $pguser) {
            $qs_username = "username=" . urlencode($username) . '&amp;';
        }
        echo "<a href='?{$qs_username}{$sort_name}={$col_id}{$link_dir}#$anchor'>";
        echo $colspec['label'];
        echo "</a>$caret";
        echo "</th>";
    }
    echo "</tr>\n";
}

function get_round_query_result($round_view, $round_sort, $round_column_specs, $username)
{
    [$order_col, $order_dir] = get_sort_col_and_dir($round_sort);
    $sql_order = sql_order_spec($round_column_specs, $order_col, $order_dir);

    $posted = get_project_status_descriptor('posted');

    if ($order_col != 'time') {
        // Add the time as a secondary ordering
        $sql_order .= ", " . sql_order_spec($round_column_specs, 'time', 'D');
    }

    if ($round_view == "available") {
        $selection_clause = "
            AND user_project_info.t_latest_page_event > 0
        ";

        // create an array of available states for rounds the user can work in
        // and select on those
        $avail_states = [];
        foreach (get_stages_user_can_work_in($username) as $stage) {
            if ($stage instanceof Round) {
                $avail_states[] = $stage->project_available_state;
            }
        }

        if ($avail_states) {
            $selection_clause .= sprintf(
                "AND projects.state in (%s)",
                surround_and_join($avail_states, "'", "'", ',')
            );
        } else {
            $selection_clause = "AND 0";
        }
        unset($round_column_specs['postednum']);
    } elseif ($round_view == "recent") {
        $selection_clause = sprintf(
            "
            AND NOT $posted->state_selector
            AND user_project_info.t_latest_page_event > %d
            ",
            strtotime("100 days ago")
        );
        unset($round_column_specs['postednum']);
    } elseif ($round_view == "posted") {
        $selection_clause = "
            AND $posted->state_selector
            AND user_project_info.t_latest_page_event > 0
        ";
        unset($round_column_specs['time']);
        unset($round_column_specs['n_available_pages']);
        unset($round_column_specs['percent_done']);
        unset($round_column_specs['days_checkedout']);
        unset($round_column_specs['state']);
    } elseif ($round_view == "bookmark") {
        $selection_clause = "
            AND user_project_info.bookmark = 1
        ";
        unset($round_column_specs['postednum']);
    } else {
        $selection_clause = "
            AND NOT $posted->state_selector
            AND user_project_info.t_latest_page_event > 0
        ";
        unset($round_column_specs['postednum']);
    }

    // We escape the username here rather than sprintf() the SQL because
    // $avail_state_clause can contain %s
    $escaped_username = DPDatabase::escape($username);
    $sql = "
        SELECT
            user_project_info.projectid,
            user_project_info.t_latest_page_event AS max_timestamp,
            projects.nameofwork,
            projects.state,
            projects.postednum,
            projects.deletion_reason,
            projects.n_pages,
            projects.n_available_pages,
            1 - (projects.n_available_pages / projects.n_pages) AS percent_done,
            (unix_timestamp() - projects.modifieddate)/(24 * 60 * 60) AS days_checkedout
        FROM user_project_info LEFT OUTER JOIN projects USING (projectid)
        WHERE user_project_info.username='$escaped_username'
            $selection_clause
        ORDER BY $sql_order
    ";
    return [DPDatabase::query($sql), $round_column_specs];
}

function get_pool_query_result($pool_view, $pool_sort, $pool_column_specs, $username)
{
    $created = get_project_status_descriptor('created');
    $proofed = get_project_status_descriptor('proofed');
    $posted = get_project_status_descriptor('posted');

    $pp_states = [
        PROJ_POST_FIRST_AVAILABLE,
        PROJ_POST_FIRST_CHECKED_OUT,
    ];
    $ppv_states = [
        PROJ_POST_SECOND_AVAILABLE,
        PROJ_POST_SECOND_CHECKED_OUT,
    ];
    $deleted_states = [
        PROJ_DELETE,
    ];
    $pp_states_selector = "state IN (" .  surround_and_join($pp_states, "'", "'", ",") . ")";
    $ppv_states_selector = "state IN (" .  surround_and_join($ppv_states, "'", "'", ",") . ")";
    $deleted_states_selector = "state IN (" .  surround_and_join($deleted_states, "'", "'", ",") . ")";

    if ($pool_view == "reserved") {
        // We escape the username here rather than sprintf() the SQL because
        // the state selectors can contain %s
        $escaped_username = DPDatabase::escape($username);
        $where_clause = "
            WHERE
                checkedoutby='$escaped_username'
                AND $created->state_selector
                AND NOT $proofed->state_selector
        ";
        unset($pool_column_specs['checkedoutby']);
        unset($pool_column_specs['postproofer']);
        unset($pool_column_specs['ppverifier']);
        unset($pool_column_specs['days_checkedout']);
        unset($pool_column_specs['postednum']);
    } elseif ($pool_view == "active") {
        // We escape the username here rather than sprintf() the SQL because
        // the state selectors can contain %s
        $escaped_username = DPDatabase::escape($username);
        $where_clause = "
            WHERE
                (
                    postproofer='$escaped_username'
                    OR (
                        (postproofer = '' OR postproofer IS NULL)
                        AND checkedoutby='$escaped_username'
                        AND $pp_states_selector
                    )
                    OR
                    ppverifier='$escaped_username'
                    OR (
                        (ppverifier = '' OR ppverifier IS NULL)
                        AND checkedoutby='$escaped_username'
                        AND $ppv_states_selector
                    )
                )
                AND NOT $deleted_states_selector
                AND NOT $posted->state_selector
        ";
        unset($pool_column_specs['postednum']);
    } elseif ($pool_view == "posted") {
        // We escape the username here rather than sprintf() the SQL because
        // the state selectors can contain %s
        $escaped_username = DPDatabase::escape($username);
        $where_clause = "
            WHERE
                (
                    postproofer='$escaped_username'
                    OR ppverifier='$escaped_username'
                    OR checkedoutby='$escaped_username'
                )
                AND $posted->state_selector
        ";
        unset($pool_column_specs['checkedoutby']);
        unset($pool_column_specs['days_checkedout']);
        unset($pool_column_specs['state']);
    }

    [$order_col, $order_dir] = get_sort_col_and_dir($pool_sort);
    if (!array_key_exists($order_col, $pool_column_specs)) {
        $order_col = 'title';
        $order_dir = 'A';
    }
    $sql_order = sql_order_spec($pool_column_specs, $order_col, $order_dir);

    if ($order_col == 'state' and in_array('days_checkedout', $pool_column_specs)) {
        // Add days_checkedout as a secondary ordering
        $sql_order .= ", " . sql_order_spec($pool_column_specs, 'days_checkedout', 'A');
    } elseif ($order_col != 'title') {
        // Add title as a secondary ordering
        $sql_order .= ", " . sql_order_spec($pool_column_specs, 'title', 'A');
    }

    $query = "
        SELECT *,
            (unix_timestamp()    - modifieddate    )/(24 * 60 * 60) AS days_checkedout
        FROM projects
        $where_clause
        ORDER BY $sql_order
    ";

    return [DPDatabase::query($query), $pool_column_specs, "{$order_col}{$order_dir}"];
}
