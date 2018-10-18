<?php
$relPath="../pinc/";
include_once($relPath.'Project.inc');
include_once($relPath.'stages.inc'); // can_user_get_pages_in_project()
include_once($relPath.'RoundDescriptor.inc'); // get_Round_for_project_state() is_a_mentor_round()
include_once($relPath.'project_states.inc'); // project_states_text()
include_once($relPath.'errors.inc'); // try_query()
include_once($relPath.'DPage.inc');
include_once($relPath.'LPage.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'user_is.inc');
include_once($relPath.'page_tally.inc');
//include_once($relPath.'abort.inc');
include_once($relPath.'maybe_mail.inc');
/*
function get_project_data($projectid)
{
    if(!$projectid)
    {
        throw new Exception(_("No project is specified"));
    }
    // Check if $projectid has the form of a valid project ID, either in its
    // long form (projectID####) or just the project number (####).
    if (1 != preg_match('/^projectID[0-9a-f]{13}$/', $projectid))
    {
        if (1 == preg_match('/^[0-9a-f]{13}$/', $projectid))
        {
            $projectid = "projectID$projectid";
        }
        else
        {
            $error = sprintf(_("'%s' is not a valid projectID."), html_safe($projectid));
            throw new Exception($error);
        }
    }
    // See if the projectID exists in the projects table
    $sql = "
        SELECT *
        FROM projects
        WHERE projectid = '$projectid'";
    $res = try_query($sql);
    $project_data = mysqli_fetch_assoc($res);
    if(!$project_data)
    {
        $error = sprintf(_("There is no project with projectid '%s'"), $projectid);
        throw new Exception($error);
    }
    return $project_data;
}

function get_project_pages($projectid, $fields)
{
    $query_fields = implode(', ', $fields);
    $sql = "SELECT $query_fields FROM $projectid";
    $res = try_query($sql);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function validate_state($project, $expected_state)
{
    if(!$expected_state)
    {
        throw new Exception(_("No project state is specified"));
    }

    $expected_state_text = project_states_text($expected_state);
    if (empty($expected_state_text))
    {
        throw new Exception("parameter 'proj_state' has a bad value: '$expected_state'");
    }
    $project_state = $project->state;
    if ($expected_state != $project_state)
    {
        $error = sprintf(_('Warning: Project "%1$s" is not in state "%2$s"; it is now in state "%3$s".'),
            $project->nameofwork, $expected_state_text, project_states_text($project->state));
        throw new Exception($error);
    }
}
*/
/* The page logic:
Pages in a round start out as 'available'.
'available' -> 'out' when first checked out. (This means it has not yet been saved in the current round).
'out' -> 'temp' on 'Save as in progress' or 'Revert to original'.
'out' and 'temp' are not changed by 'resume'(from project page), or 'stop proofreading'.
'revert to last save' is not available for 'out' state.
'temp' persists for 'revert to last save' and 'Revert to original'.
'Revert to original' does a save before reverting.
for 'save as done', 'out' and 'temp' -> 'saved'.
for 'return to round', 'out' and 'temp' -> 'available'.
for 'resume'(from project page) 'saved' -> 'temp'
*/
function get_available_page1( $projectid, $proj_state, $pguser)
// Returns an LPage, unless no page is available,
// in which case throws an exception.
{
    global $preceding_proofer_restriction;
    // Make sure project is still in same state.
    $project = new Project(get_project_data($projectid));
    validate_state($project, $proj_state);

    $round = get_Round_for_project_state($proj_state);

    // Normally, pages are served in order of "page number"
    // (i.e., by the 'image' field)
    $order = 'image ASC';
    // but this can be overridden below.

    $err = can_user_get_pages_in_project( $pguser, $project, $round );
    if ($err)
    {
        throw new Exception($err);
    }
    if ( $project->difficulty == 'beginner' and $round->is_a_mentor_round() )
    {
            // For beginner projects in a mentor round,
            // we serve up pages in a different order,
            // to facilitate mentoring:
            // 1) Mentors like to give prompt feedback.
            // 2) Mentors like to deal with all of one proofreader's pages at a time.
            //
            // We look at the save-times recorded in the mentee round for pages
            // in this project, and for each mentee, ascertain their earliest
            // such save-time, and then order the mentees according to that time.
            // (So the mentee with the "longest-waiting" page comes first.
            // In the unlikely event that two mentees have exactly the same
            // earliest-save-time (down to the second), we break the tie by
            // taking the users in alphabetical order by username.)
            //
            // Taking the mentees in the above order, we serve each one's pages
            // in 'book order' (i.e., ordered by 'image' field).
            //
            // (See http://www.pgdp.net/phpBB3/viewtopic.php?p=1027908#p1027908 and following.)
            $order = "
                (
                    SELECT MIN({$round->mentee_round->time_column_name})
                    FROM $projectid
                    WHERE {$round->mentee_round->user_column_name}
                      = p.{$round->mentee_round->user_column_name}
                ),
                {$round->mentee_round->user_column_name},
                image
            ";
    }

    // The page to be retrieved must be an available page, of course.
    $restrictions = "state='{$round->page_avail_state}'";

    // Are there any other restrictions that the page must satisfy?
    // (This should maybe be a property of $round.)
    if ( empty($preceding_proofer_restriction) )
    {
        // Nope, no other restrictions.
    }
    else if ($preceding_proofer_restriction == 'not_immediately_preceding')
    {
        // Don't give this user a page that they worked on in the preceding round.
        // (Where "preceding" takes into account skipped rounds.)
        if ( $round->round_number > 1 )
        {
            // We need an SQL expression for the preceding proofreader.
            $preceding_proofer = "CASE";
            for ( $rn = $round->round_number-1; $rn > 0; $rn-- )
            {
                $earlier_round = get_Round_for_round_number( $rn );
                $ucn = $earlier_round->user_column_name;
                $preceding_proofer .= " WHEN LENGTH($ucn) THEN $ucn";
            }
            // What if all of the earlier rounds were skipped?
            // (It's pretty unlikely, but we should allow for it.)
            // All of the WHEN LENGTH(...) clauses will fail.
            //
            // If the CASE expr doesn't have an ELSE clause, it will yield NULL,
            // so we'll end up requiring that
            //     NULL != '$pguser'
            // which is always NULL (effectively false), so no pages will
            // satisfy the restriction, which is not what we want.
            //
            // Instead, add an ELSE clause saying that the preceding proofreader's
            // name was the empty string. So we'll end up requiring that
            //     '' != '$pguser'
            // which is always true, so any (available) page will satisfy the
            // restriction, which is what we want.
            $preceding_proofer .= " ELSE ''";
            $preceding_proofer .= " END";

            $restrictions .= " AND $preceding_proofer != '$pguser'";
        }
    }
    else if ($preceding_proofer_restriction == 'not_previously_proofed')
    {
        // Don't give this user a page that they have worked on before.
        for ( $rn = 1; $rn < $round->round_number; $rn++ )
        {
            $earlier_round = get_Round_for_round_number( $rn );
            $restrictions .= " AND {$earlier_round->user_column_name} != '$pguser'";
        }
    }
    else
    {
        // The empty/null (i.e., no restriction) case has already been dealt with.
        throw new Exception("Bad value for 'preceding_proofer_restriction': '$preceding_proofer_restriction'.");
    }

    // Find page to be proofread and update to avoid two people getting the same page
    $new_state = $round->page_out_state;
    $timestamp = time();
    $settings = " state= '$new_state', {$round->time_column_name}=$timestamp, {$round->user_column_name}='$pguser' ";
    $sql = "
    UPDATE $projectid
    SET image = (@imageid := image), $settings
    WHERE $restrictions
    ORDER BY $order
    LIMIT 1";

    $res = try_query($sql);
    $res = try_query("SELECT @imageid");
    $imagefile = mysqli_fetch_row($res)[0];
    if(!$imagefile)
    {
        throw new Exception(_("No more files are available for proofreading in this round of the project."));
    }

    _log_page_event( $projectid, $imagefile, 'checkout', $pguser, $round, TRUE );
    _project_adjust_n_available_pages( $projectid, -1 );
    $lpage = new LPage( $projectid, $imagefile, $new_state, 0 );
    return $lpage;
}

function get_indicated_LPage1(
    $projectid,
    $proj_state,
    $imagefile,
    $page_state,
    $reverting_to_orig )
// Returns an LPage, unless the parameters do not correctly identify a page,
// in which case throws exception
{
    // Make sure project is still in same state.
    $project = new Project(get_project_data($projectid));
    validate_state($project, $proj_state);

    $sql = "
        SELECT state
        FROM $projectid
        WHERE image='$imagefile'
    ";
    $res = try_query($sql);
    $row = mysqli_fetch_row($res);
    if (!$row)
    {
        // This should only happen if they've been tweaking URLs.
        // Or if the page has actually been deleted.
        throw new Exception(_("No such page '$imagefile'"));
    }

    list($current_page_state) = $row;

    if ($page_state != $current_page_state)
    {
        $err = sprintf(
            _('Page %1$s has changed state from %2$s to %3$s, so your request is invalid.'),
            $imagefile, $page_state, $current_page_state );
        throw new Exception($err);
    }

    return new LPage($projectid, $imagefile, $page_state, $reverting_to_orig);
}
