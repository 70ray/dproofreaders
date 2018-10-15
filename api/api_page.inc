<?php
$relPath="../pinc/";
include_once($relPath.'stages.inc'); // can_user_get_pages_in_project()
include_once($relPath.'RoundDescriptor.inc'); // get_Round_for_project_state() is_a_mentor_round()
include_once('errors.inc'); // throw_sql_error
include_once($relPath.'DPage.inc');

// from LPage.inc modified to throw errors and return image filename
function get_available_page($project, $proj_state)
{
    global $preceding_proofer_restriction, $pguser;

    $projectid = $project->projectid;
    $round = get_Round_for_project_state($proj_state);

    // Normally, pages are served in order of "page number"
    // (i.e., by the 'image' field)
    $order = 'image ASC';
    // but this can be overridden below.

    $error = can_user_get_pages_in_project( $pguser, $project, $round );
    if ( $error )
    {
        throw new Exception($error);
    }
//throw new Exception("e1");
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
        $error = "Bad value for 'preceding_proofer_restriction': '$preceding_proofer_restriction'.";
        throw new Exception($error);
    }

    // Find page to be proofread.
    // (It appears that a simultaneous query from someone else would return the same page.)
    $dbQuery = "
        SELECT image, state
        FROM $projectid AS p
        WHERE $restrictions
        ORDER BY $order
        LIMIT 1
    ";
    $result = mysqli_query(DPDatabase::get_connection(), $dbQuery);
    if (!$result)
    {
        throw_sql_error($dbQuery);
    }
    $npage = mysqli_fetch_assoc($result);
    if(!$npage)
    {
        $error = _("No more files available for proofreading for this round of the project.");
        throw new Exception($error);
    }
    $image = $npage['image'];
//throw new Exception("e2");

    Page_checkout( $projectid, $image, $round, $pguser);

    return $image;
}
