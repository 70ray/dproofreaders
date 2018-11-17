<?php

include_once($relPath.'project_states.inc');
include_once($relPath.'project_trans.inc');
include_once($relPath.'site_vars.php');
include_once($relPath.'stages.inc');
include_once($relPath.'user_is.inc');
include_once($relPath.'errors.inc'); // try_query()
include_once($relPath.'DPage.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'page_tally.inc');
include_once($relPath.'project_continuity.inc');
//include_once($relPath.'abort.inc');
include_once($relPath.'maybe_mail.inc');
include_once($relPath.'projectinfo.inc');

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

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

// The page logic:
//Pages in a round start out as 'available'.
//'available' -> 'out' when first checked out. (This means it has not yet been saved in the current round).
//'out' -> 'temp' on 'Save as in progress' or 'Revert to original'.
//'out' and 'temp' are not changed by 'resume'(from project page), or 'stop proofreading'.
//'revert to last save' is not available for 'out' state.
//'temp' persists for 'revert to last save' and 'Revert to original'.
//'Revert to original' does a save before reverting.
//for 'save as done', 'out' and 'temp' -> 'saved'.
//for 'return to round', 'out' and 'temp' -> 'available'.
//for 'resume'(from project page) 'saved' -> 'temp'

function get_available_page( $projectid, $proj_state)
// Returns an LPage, unless no page is available,
// in which case throws an exception.
{
    global $preceding_proofer_restriction, $pguser;
    // Make sure project is still in same state.
    $project = new Project($projectid);
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

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function get_indicated_LPage(
    $projectid,
    $proj_state,
    $imagefile,
    $page_state)
// Returns an LPage, unless the parameters do not correctly identify a page,
// in which case throws exception
{
    // Make sure project is still in same state.
    $project = new Project($projectid);
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
        $err = sprintf(_("Page '%s' does not exist"), $imagefile);
        throw new Exception($err);
    }

    list($current_page_state) = $row;

    if ($page_state != $current_page_state)
    {
        $err = sprintf(
            _('Page %1$s has changed state from %2$s to %3$s, so your request is invalid.'),
            $imagefile, $page_state, $current_page_state );
        throw new Exception($err);
    }

    return new LPage($projectid, $imagefile, $page_state);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

class LPage
// The "L" stands for "Logic",
// as in the 3 tiers of Data, Logic, and Presentation.
// (For pages, the Data layer is pinc/DPage.inc,
// and the Presentation layer is most of tools/proofers/*.
// However, we're not very strict about the divisions yet.)
{
    function __construct($projectid, $imagefile, $page_state)
    {
        $this->projectid  = $projectid;
        $this->imagefile  = $imagefile;
        $this->page_state = $page_state;
        $this->reverting_to_orig = 0;

        // It's debatable whether LPage should have a 'reverting_to_orig' property.
        // You could argue that it's an interface-level artifact,
        // and thus belongs in PPage.

        $this->round = get_Round_for_page_state($page_state);
    }

    // -------------------------------------------------------------------------

    function is_formatting_round()
    {
        return is_formatting_round($this->round);
    }

    function can_be_reverted_to_last_save()
    {
        return ( $this->reverting_to_orig==1
            || $this->page_state == $this->round->page_save_state
            || $this->page_state == $this->round->page_temp_state
        );
    }

    function get_text()
    {
        if ( $this->reverting_to_orig==1
            || $this->page_state == $this->round->page_avail_state
            || $this->page_state == $this->round->page_out_state )
        {
            $desired_column_name = $this->round->prevtext_column_name;
        }
        else
        {
            $desired_column_name = $this->round->text_column_name;
        }

        $q = "
            SELECT $desired_column_name
            FROM {$this->projectid}
            WHERE image='{$this->imagefile}'
        ";
        $res = mysqli_query(DPDatabase::get_connection(), $q);
        list($page_text) = mysqli_fetch_row($res);

        return $page_text;
    }
/*
    function get_language()
    {
        $project = new Project($this->projectid);
        $lang = proj_lang_code($project->language,"primary");
        return $lang;
    }
*/
    function get_username_for_round( $round )
    {
        $res = mysqli_query(DPDatabase::get_connection(), "
            SELECT {$round->user_column_name}
            FROM {$this->projectid}
            WHERE image = '{$this->imagefile}'
        ");
        $row = mysqli_fetch_assoc($res);
        return $row[$round->user_column_name];
    }

    // -------------------------------------------------------------------------
/*
    function checkout( $user )
    {
        $this->page_state =
            Page_checkout( $this->projectid, $this->imagefile, $this->round, $user );
    }
*/
    function saveAsInProgress($page_text)
    {
        global $pguser;

        $round = $this->round;
        $this->Page_require(array($round->page_out_state, $round->page_temp_state ), 'saveAsInProgress');
        $timestamp = time();
        $page_text = _normalize_page_text($page_text);
        $this->Page_UPDATE(sprintf("
            state='{$round->page_temp_state}',
            {$round->time_column_name}=$timestamp,
            {$round->user_column_name}='$pguser',
            {$round->text_column_name}='%s'
        ", mysqli_real_escape_string(DPDatabase::get_connection(), $page_text)));
        $this->log_page_event('saveAsInProgress');
        $this->page_state = $round->page_temp_state;
        $this->reverting_to_orig = 0;
    }

    function saveAsDone($page_text)
    {
        global $pguser;

        $round = $this->round;
        $this->Page_require( array( $round->page_out_state, $round->page_temp_state ), 'saveAsDone' );

        $timestamp = time();
        $page_text = _normalize_page_text($page_text);
        $this->Page_UPDATE(sprintf("
            state='{$round->page_save_state}',
            {$round->time_column_name}=$timestamp,
            {$round->user_column_name}='$pguser',
            {$round->text_column_name}='%s'
        ", mysqli_real_escape_string(DPDatabase::get_connection(), $page_text)));

        $this->log_page_event('saveAsDone');
        _project_set_t_last_page_done( $this->projectid, $timestamp );
        $this->page_state = $round->page_save_state;
        // add to user page count
        page_tallies_add( $round->id, $pguser, +1 );
    }

    function returnToRound()
    {
        global $pguser;

        $round = $this->round;
        $this->Page_require(array($round->page_out_state, $round->page_temp_state), 'returnToRound' );
        $timestamp = time();

        $this->Page_UPDATE("
            state='{$round->page_avail_state}',
            {$round->time_column_name}=$timestamp,
            {$round->user_column_name}='$pguser'
        " );

        $this->log_page_event('returnToRound');
        _project_adjust_n_available_pages( $this->projectid, +1 );
        $this->page_state = $round->page_avail_state;
    }

    function revertToOriginal()
    {
        $this->reverting_to_orig = 1;
    }

    function revertToSaved()
    {
        $round = $this->round;
        $this->Page_require(array($round->page_temp_state ), 'revertToSaved' );

        $this->page_state = $round->page_temp_state;
        $this->reverting_to_orig = 0;
    }

    function markAsBad($reason )
    // Return TRUE if this report causes project to be marked bad.
    {
        global $code_url, $site_abbreviation, $PAGE_BADNESS_REASONS, $pguser;

        $round = $this->round;
        $this->Page_require(array($round->page_out_state, $round->page_temp_state), 'markAsBad' );

        $this->Page_UPDATE("
            state='{$round->page_bad_state}',
            b_user='$pguser',
            b_code=$reason,
            {$round->text_column_name}={$round->prevtext_column_name}
        " );

        $this->log_page_event('markAsBad');

        // Advise PM that the page has been marked bad.
		$body_blurb =
"Page {$this->imagefile} of this project has been marked bad due to {$PAGE_BADNESS_REASONS[$reason]}.
Please visit
    $code_url/tools/project_manager/handle_bad_page.php?projectid={$this->projectid}&image={$this->imagefile}
to make any needed changes and make the page available for proofreading again.
Until this report has been resolved, the project will not be able to leave
the current round. If 10 pages are marked bad by at least 3 different users,
the project will automatically be made unavailable.";
        maybe_mail_project_manager($this->projectid, $body_blurb, "$site_abbreviation: Bad Page");

        // Now determine whether the project as a whole should be marked bad.
        // We need 10 or more bad pages reported by 3 or more distinct users.
        // (Note duplication of code with pages_indicate_bad_project() in automodify.php)

        $n_bad_pages =
            Project_getNumPagesInState(
                $this->projectid, $round->page_bad_state );
        if ( $n_bad_pages < 10 ) return FALSE;

        $n_distinct_reporters =
            Project_getNumPagesInState(
                $this->projectid, $round->page_bad_state, "DISTINCT(b_user)" );
        if ($n_distinct_reporters < 3) return FALSE;

        $error_msg = project_transition( $this->projectid, $round->project_bad_state, PT_AUTO );
        if ($error_msg)
        {
            throw new Exception("$error_msg");
        }
        return TRUE;
    }

    // -----------------------------------------------------------------------------

    function resume_saved_page()
    {
        global $pguser;

        $round = $this->round;
        if ($this->page_state == $round->page_save_state)
        {
            // Page comes from DONE.
            // no need to update text, just record state change
            $this->Page_require(array( $round->page_save_state), 'reopen' );
            $timestamp = time();

            $this->Page_UPDATE("
                state='{$round->page_temp_state}',
                {$round->time_column_name}=$timestamp,
                {$round->user_column_name}='$pguser'
            " );

            $this->log_page_event('reopen');
            $this->page_state = $round->page_temp_state;

            // When it was saved, the user's page-count was incremented.
            // Now they are 'unsaving' it, so decrement their page-count.
            // They'll get it back if/when they save-as-done again.
            // (Plugs a former page-count cheat.)

            page_tallies_add( $round->id, $pguser, -1 );
        }
        else
        {
            // Page comes from IN PROGRESS (temp or out).

            // Resuming such a page has no actual effect on the page,
            // though it might in the future.

            // For now, just confirm that the user is entitled to resume
            // this page. (In the DONE case, Page_reopen performs this check.)
            $this->Page_require(array($round->page_out_state, $round->page_temp_state), 'resumeSavedPage' );
        }
    }

    function Page_require($allowed_states, $op_name )
    {
        global $pguser;

        _Page_require($this->projectid, $this->imagefile, $allowed_states, $this->round->user_column_name, $pguser, $op_name);
    }

    function Page_UPDATE($settings )
    {
        try_query("UPDATE $this->projectid SET $settings WHERE image='$this->imagefile'");
    }

    function log_page_event($event_type)
    {
        global $pguser;

        _log_page_event($this->projectid, $this->imagefile, $event_type, $pguser, $this->round, true);
    }
} // end class

// vim: sw=4 ts=4 expandtab
