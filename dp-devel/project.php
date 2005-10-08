<?PHP
$relPath='./pinc/';

include_once($relPath.'dp_main.inc');
include_once($relPath.'v_site.inc');
include_once($relPath.'gettext_setup.inc');
include_once($relPath.'stages.inc');
include_once($relPath.'Project.inc');
include_once($relPath.'ProjectTransition.inc');
include_once($relPath.'project_states.inc');
include_once($relPath.'projectinfo.inc'); // project_getnumavailablepagesinround()
include_once($relPath.'comment_inclusions.inc'); // parse_project_comments()
include_once($relPath.'../tools/project_manager/page_table.inc'); // echo_page_table
include_once($relPath.'user_is.inc');
include_once($relPath.'SettingsClass.inc');
include_once($relPath.'pg.inc');          // get_pg_catalog_link...
include_once($relPath.'theme.inc');
include_once($relPath.'../tools/project_manager/projectmgr.inc'); // echo_manager_header
include_once($relPath.'postcomments.inc'); // get_formatted_postcomments(...)

error_reporting(E_ALL);

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// Usually, the user arrives here by clicking on the title of a project
// in a list of projects.
// But there are lots of other less-used pages that link here.

$projectid      = @$_GET['id'];
$expected_state = @$_GET['expected_state'];
$detail_level   = @$_GET['detail_level'];

$VALID_DETAIL_LEVELS = array('1','2','3','4');
if ( is_null($detail_level) )
{
    // unspecified
    $detail_level = 2;
}
elseif ( in_array($detail_level, $VALID_DETAIL_LEVELS ) )
{
    // fine
    $detail_level = intval($detail_level);
}
else
{
    die("bad 'detail_level' parameter: '$detail_level'");
}

// -----

$project = new Project( $projectid );

// -----------------------------------------------------------------------------

// In a tabbed browser, the page-title passed to theme() will appear in
// the tab, which tends to be small, as soon as you have a few of them.
// So, put the distinctive part of the page-title (i.e. the name of the
// project) first.
$title_for_theme = sprintf( _('"%s" project page'), $project->nameofwork );

$title = sprintf( _("Project Page for '%s'"), $project->nameofwork );

do_update_pp_activity();

if ($detail_level==1)
{
    echo "<h1>$title</h1>\n";

    do_expected_state();
    do_detail_level_switch();

    do_project_info_table();

    /*
    echo "<BR>";

    echo "<p><b>";
    echo _("This information has been opened in a separate browser window, feel free to leave it open for reference or close it.");
    echo "</b></p>";
    */
}
else
{
    // Detail level 2 (the default) should show the information
    // that is usually wanted by the people who usually work with
    // the project in its current state.

    // don't show the stats column
    $no_stats=1;
    theme($title_for_theme, "header");

    do_pm_header();

    echo "<h1>$title</h1>\n";

    do_detail_level_switch();
    do_expected_state();

    list($top_blurb, $bottom_blurb) = decide_blurbs();

    do_blurb_box( $top_blurb );
    do_project_info_table();
    do_edit_above();
    do_blurb_box( $bottom_blurb );

    do_early_uploads();
    do_post_downloads();
    do_postcomments();
    do_smooth_reading();
    do_change_state();

    if ($detail_level >= 3)
    {
        // Stuff that's (usually) only of interest to
        // PMs/PFs/SAs and curious others.
        do_images();
        do_extra_files();
        do_page_summary();
        if ($detail_level >= 4)
        {
            do_page_table();
        }
        do_detail_level_switch();
    }

    theme('', 'footer');
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_update_pp_activity()
{
// If the project has been checked out for more than 90 days and
// the pp-er loads the page, it's interpreted as a sign of
// activity. Update the database accordingly.

    global $project;

    if ( $project->state != PROJ_POST_FIRST_CHECKED_OUT ) return;

    if (! $project->PPer_is_current_user) return;

    // 7776000 = 90*24*60*60 seconds
    if ($project->modifieddate < time()-7776000) {
      // Modified more than 90 days ago. The PP-er,
      // by loading this page, is reporting
      // activity.

      $projectid = $project->projectid;
      $now = time();
      mysql_query("UPDATE projects SET modifieddate=$now WHERE projectid='$projectid'");
      $project->modifieddate = $now;
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_pm_header()
{
    global $project;
    if (!$project->can_be_managed_by_current_user) return;

    echo_manager_header( 'project_detail_page' );
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_detail_level_switch()
{
    global $project, $detail_level, $VALID_DETAIL_LEVELS;

    echo sprintf(
        _('This page is being presented at detail level %d.'),
        $detail_level
    );
    echo "\n";
    echo _('Switch to:'), "\n";
    foreach( $VALID_DETAIL_LEVELS as $v )
    {
        if ( $v != $detail_level )
        {
            $url = "project.php?id={$project->projectid}&amp;expected_state={$project->state}&amp;detail_level=$v";
            echo "<a href='$url'>$v</a>\n";
        }
    }
    echo "<br>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_expected_state()
{
    global $project, $expected_state;

    if ( !empty($expected_state) && $expected_state != $project->state )
    {
        echo "<font color='red'>";
        echo sprintf(
            _("Warning! The project is no longer in '%s'. It is now in '%s'."),
            project_states_text($expected_state),
            project_states_text($project->state)
        );
        echo "</font>";
        echo "<br>\n";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function decide_blurbs()
{
    global $project, $code_url, $pguser;

    $projectid = $project->projectid;
    $state = $project->state;

    $round = get_Round_for_project_state($state);
    if (is_null($round))
    {
        return array(null,null);
    }

    if ( $state != $round->project_available_state )
    {
        $text = _('Users are not allowed to work on the project in its current state');
        return array( $text, $text );
    }

    $uao = $round->user_access($pguser);

    if ( !$uao->can_access )
    {
        $text = _('You are not permitted to work in this round.');
        return array( $text, $text );
    }

    $num_pages_available =
        Project_getNumPagesInState( $projectid, $round->page_avail_state );

    if ( $num_pages_available == 0 )
    {
        $top_blurb = $bottom_blurb =
            _("Round Complete")
            . "<br>"
            . _("There are no pages available for proofreading.");
    }
    else if ( $project->difficulty == 'beginner' && !user_can_work_on_beginner_pages_in_round($round) )
    {
        $top_blurb = $bottom_blurb =
            _("You have reached your quota of pages from 'Beginners Only' projects in this round.");
    }
    else
    {
        // If there's any proofreading to be done, this is the link to use.
        $url = "$code_url/tools/proofers/proof.php?project=$projectid&amp;proofstate=$state";
        $label = _("Start Proofreading");
        $proofreading_link = "<b><a href='$url'>$label</a></b>";

        // When were the project comments last modified?
        $comments_timestamp = $project->modifieddate;
        $comments_time_str = strftime(_("%A, %B %e, %Y at %X"), $comments_timestamp);
        $comments_last_modified_blurb = _("Project Comments last modified:") . " " . $comments_time_str;

        // Other possible components of blurbs:
        $please_scroll_down = _("Please scroll down and read the Project Comments for any special instructions <b>before</b> proofreading!");
        $the_link_appears_below = _("The 'Start Proofreading' link appears below the Project Comments");
        $comments_have_changed =
            "<font color='red'>"
            . "<b>"
            . _("Project Comments have changed!")
            . "</b>"
            . "</font>";

        // ---

        $bottom_blurb =
            $comments_last_modified_blurb
            . "<br>"
            . $proofreading_link;

        // Has the user saved a page of this project since the comments were
        // last changed? If not, it's unlikely they've seen the revised comments.
        $res = mysql_query("
            SELECT {$round->time_column_name}
            FROM $projectid
            WHERE state='{$round->page_save_state}' AND {$round->user_column_name}='$pguser'
            ORDER BY {$round->time_column_name} DESC
            LIMIT 1
        ");
        if (mysql_num_rows($res) == 0)
        {
            // The user has not saved any pages for this project.
            $top_blurb =
                $please_scroll_down
                . "<br>"
                . $comments_last_modified_blurb
                . "<br>"
                . $the_link_appears_below;
        }
        else
        {
            // The user has saved a page for this project.
            $my_latest_save_timestamp = mysql_result($res,0,$round->time_column_name);

            if ($my_latest_save_timestamp < $comments_timestamp)
            {
                // The latest page-save was before the comments were revised.
                // The user probably hasn't seen the revised project comments.
                $top_blurb =
                    $comments_have_changed
                    . "<br>"
                    . $please_scroll_down
                    . "<br>"
                    . $comments_last_modified_blurb
                    . "<br>"
                    . $the_link_appears_below;
            }
            else
            {
                // The latest page-save was after the comments were revised.
                // We'll assume that the user has read the comments.
                $top_blurb =
                    $please_scroll_down
                    . "<br>"
                    . $comments_last_modified_blurb
                    . "<br>"
                    . $proofreading_link;
            }
        }
    }

    return array( $top_blurb, $bottom_blurb );
}

// -----------------------------------------------

function do_blurb_box( $blurb )
{
    if ( is_null($blurb) ) return;

    echo "<br>";
    echo "<table width='630' bgcolor='DDDDDD'>";
    echo "<tr><td align='center'>";
    echo $blurb;
    echo "</td></tr>";
    echo "</table>";
    echo "<br>";
    echo "\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_project_info_table()
{
    global $project, $code_url;

    $projectid = $project->projectid;
    $state = $project->state;

    $round = get_Round_for_project_state($state);
    // Note that $round may be NULL;

    echo "<table border=1 width=630>";

    // -------------------------------------------------------------------------
    // The state of the project

    $available_for_SR = (
        $project->state == PROJ_POST_FIRST_CHECKED_OUT &&
        $project->smoothread_deadline > time() );

    $right = project_states_text($project->state);
    if ($round)
    {
        $extra = $round->description;
        $right = "$right ($extra)";
    }
    elseif ($available_for_SR)
    {
        $sr_deadline_str = strftime(
            _("%A, %B %e, %Y"), $project->smoothread_deadline );
        $sr_sentence = sprintf(
            _('This project has been made available for smooth reading until %s.'),
            "<b>$sr_deadline_str</b>"
        );
        $extra2 = _('See below.');
        $right = "$right<br>$sr_sentence $extra2";
    }
    echo_row_a( _("Project State"), $right );

    // -------------------------------------------------------------------------
    // Information about the work itself (independent of DP)

    echo_row_a( _("Title"),           $project->nameofwork );
    echo_row_a( _("Author"),          $project->authorsname );
    echo_row_a( _("Language"),        $project->language );
    echo_row_a( _("Genre"),           $project->genre );
    echo_row_a( _("Difficulty"),      $project->difficulty );

    // -------------------------------------------------------------------------
    // Basic DP info

    if (!empty($project->special_code))
    {
        $spec_code = $project->special_code;
        if (
            (strncmp($spec_code,'Birthday',8) == 0 ) or
            (strncmp($spec_code,'Otherday',8) == 0 )
        )
        {
            $spec_display = $spec_code;
        }
        else
        {
            $spec_res = mysql_fetch_assoc(mysql_query("
                SELECT display_name
                FROM special_days
                WHERE spec_code = '$spec_code'
            "));
            if ($spec_res)
            {
                $spec_display = $spec_res['display_name'];
            }
            else
            {
                $spec_display = "($spec_code)";
            }
        }

        echo_row_a( _("Special Day"), $spec_display );
    }

    // -------

    echo_row_a( _("Project ID"), $project->projectid );

    // The clearance line normally contains the email address of the
    // person who submitted the clearance request. Since this is
    // private information, we restrict who can see it.
    if ( $project->PPVer_is_current_user || $project->can_be_managed_by_current_user )
    {
        echo_row_a( _("Clearance Line"), $project->clearance, TRUE );
    }

    // -------------------------------------------------------------------------
    // People who have certain roles with respect to the project

    if (isset($project->image_source_name))
    {
        echo_row_a( _("Image Source"), $project->image_source_name, TRUE );
    }

    echo_row_a( _("Project Manager"), $project->username );

    {
        if ( !empty($project->postproofer) )
        {
            $PPer = $project->postproofer;
        }
        else if ( !empty($project->checkedoutby) &&
            !in_array(
                $project->state,
                array(
                    PROJ_POST_SECOND_CHECKED_OUT,
                    PROJ_POST_COMPLETE,
                    PROJ_SUBMIT_PG_POSTED,
                    PROJ_CORRECT_CHECKED_OUT,
                )
            )
        )
        {
            $PPer = $project->checkedoutby;
        }
        if ( isset($PPer) )
        {
            echo_row_a( _("Post Processor"), $PPer );
        }
    }

    {
        if ( !empty($project->ppverifier) )
        {
            $PPVer = $project->ppverifier;
        }
        else if ( !empty($project->checkedoutby) &&
            in_array(
                $project->state,
                array(
                    PROJ_POST_SECOND_CHECKED_OUT,
                    PROJ_POST_COMPLETE,
                    PROJ_SUBMIT_PG_POSTED,
                )
            )
        )
        {
            $PPVer = $project->checkedoutby;
        }
        if ( isset($PPVer) )
        {
            echo_row_a( _("PP Verifier"), $PPVer );
        }
    }

    global $site_supports_corrections_after_posting;
    if ($site_supports_corrections_after_posting)
    {
        // included for completeness
        if ( !empty($project->checkedoutby) &&
            $project->state == PROJ_CORRECT_CHECKED_OUT
        )
        {
            $CorrectionsReviewer = $project->checkedoutby;
        }
        if ( isset($CorrectionsReviewer) )
        {
            echo_row_a( _("Corrections Reviewer"), $CorrectionsReviewer );
        }
    }

    echo_row_a( _("Credits line so far"), $project->credits_line, TRUE );

    // -------------------------------------------------------------------------
    // Current activity

    if ($round)
    {
        $proofdate = mysql_query("
            SELECT {$round->time_column_name}
            FROM $projectid
            WHERE state='{$round->page_save_state}'
            ORDER BY {$round->time_column_name} DESC
            LIMIT 1
        ");
        if (mysql_num_rows($proofdate)!=0)
        {
            $latest_save_time = mysql_result($proofdate,0,$round->time_column_name);
            $formatted_lst = strftime(_("%A, %B %e, %Y at %X"), $latest_save_time);
            $formatted_now = strftime(_("%X"),time());
            $lastproofed = "$formatted_lst&nbsp;&nbsp;&nbsp; ("._("Current Time:")." $formatted_now)";
        }
        else
        {
            $lastproofed = _("Project has not been proofread in this round.");
        }
        echo_row_a( _("Last Proofread"), $lastproofed );
    }

    // -------------------------------------------------------------------------

    $state = $project->state;
    if ( $state == PROJ_SUBMIT_PG_POSTED
      || $state == PROJ_CORRECT_AVAILABLE
      || $state == PROJ_CORRECT_CHECKED_OUT )
    {
        $postednum = $project->postednum;
        echo_row_a(
            _("PG etext number"),
            get_pg_catalog_link_for_etext($postednum, $postednum) );
    }

    // -------------------------------------------------------------------------
    // Forum topic

    $topic_id = $project->topic_id;
    if (!empty($topic_id))
    {
        $last_post = mysql_query("SELECT post_time FROM phpbb_posts WHERE topic_id = $topic_id ORDER BY post_time DESC LIMIT 1");
        $last_post_date = mysql_result($last_post,0,"post_time");
        $last_post_date = strftime(_("%A, %B %e, %Y at %X"), $last_post_date);
        echo_row_a( _("Last Forum Post"), $last_post_date );
    }

    if ($topic_id == "")
    {
        $blurb = _("Start a discussion about this project");
    }
    else
    {
        $blurb = _("Discuss this project");
    }
    $url = "$code_url/tools/proofers/project_topic.php?project=$projectid";
    echo_row_a( _("Forum"), "<a href='$url'>$blurb</a>" );

    // -------------------------------------------------------------------------

    global $detail_level;
    if ($detail_level >= 4)
    {
        // We'll call do_page_table later, so we don't need the "Page Detail" link.
    }
    else
    {
        $url = "$code_url/tools/project_manager/page_detail.php?project=$projectid&show_image_size=0";
        $blurb = _("Images, Pages Proofread, & Differences");

        $url2 = "$url&select_by_user";
        $blurb2 = _("Just my pages");

        echo_row_a( _("Page Detail"), "<a href='$url'>$blurb</a> &gt;&gt;<a href='$url2'>$blurb2</a>&lt;&lt;");
    }

    // -------------------------------------------------------------------------
    // Personal data with respect to this project
    // (This is the only section that uses $pguser and $userP.)

    global $pguser, $userP;

    $temp = mysql_query("
        SELECT *
        FROM usersettings
        WHERE username = '$pguser' AND setting = 'posted_notice' AND value = '$projectid'
    ");
    if (mysql_num_rows($temp) == 0)
    {
        $blurb = _("Click here to register for automatic email notification of when this has been posted to Project Gutenberg.");
    }
    else
    {
        $blurb = _("Click here to <b>undo</b> registration for automatic email notification of when this has been posted to Project Gutenberg.");
    }
    $url = "$code_url/tools/proofers/posted_notice.php?project=$projectid&proofstate=$state";
    echo_row_a( _("Book Completed:"), "<a href='$url'>$blurb</a>" );

    global $detail_level;
    if ($round && $detail_level > 1)
    {
        recentlyproofed(0);
        recentlyproofed(1);
    }

    // -------------------------------------------------------------------------
    // Comments

    $postcomments = get_formatted_postcomments($project->projectid);

    if ($postcomments != '')
    {
        if ( $available_for_SR )
        {
            echo_row_b( _("Instructions for Smooth Reading"), '' );
            echo_row_c( $postcomments );
        }
        elseif ( $project->PPer_is_current_user || $project->PPVer_is_current_user
            || $project->can_be_managed_by_current_user )
        {
            echo_row_b( _("Post Processor Comments"), '' );
            echo_row_c( $postcomments );
        }
    }

    // --------

    $comments = $project->comments;

    // automatically prepend R2 intro for Beginners Only
    if ($project->difficulty == "beginner")
    {
        if ($state==PROJ_P2_AVAILABLE)
        {
            $comments = "[template=BGr2.txt]".$comments;
        }
    }

    // insert e.g. templates and biographies
    $comments = parse_project_comments($comments);

    if ( $comments == '' )
    {
        // Put in *something*, otherwise it'll probably look odd.
        $comments = '&nbsp;';
    }

    if ($round)
    {
        $a = sprintf(
                _("The <a href='%s'>Guidelines</a> give detailed instructions for working in this round."),
                "$code_url/faq/{$round->document}"
            );
        $b = _('The instructions below are particular to this project, and <b>take precedence over those guidelines</b>.');
        $comments_blurb = "$a<br>$b";
    }
    else
    {
        $comments_blurb = "";
    }
    echo_row_b( _("Project Comments"), $comments_blurb );

    echo_row_c( $comments );

    // -------------------------------------------------------------------------

    echo "</table>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function echo_row_a( $left, $right, $escape_right=FALSE )
{
    if ($escape_right) $right = htmlspecialchars( $right, ENT_NOQUOTES );
    echo "<tr>";
    echo "<td bgcolor='CCCCCC' align='center'><b>$left</b></td>";
    echo "<td colspan='4'>$right</td>";
    echo "</tr>\n";
}

function echo_row_b( $top, $bottom, $bgcolor = 'CCCCCC' )
{
    echo "<tr>";
    echo "<td colspan='5' bgcolor='$bgcolor' align='center'>";
    echo "<font size='+1'><b>$top</b></font>";
    if ($bottom)
    {
        echo "<br>$bottom";
    }
    echo "</td>";
    echo "</tr>\n";
}

function echo_row_c( $content )
{
    echo "<tr>";
    echo "<td colspan=5>";
    echo $content;
    echo "</td>";
    echo "</tr>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function recentlyproofed( $wlist )
{
    global $project, $code_url, $pguser, $userP;

    $projectid = $project->projectid;
    $state = $project->state;

    $round = get_Round_for_project_state($state);
    assert($round);

    if ($wlist==0)
    {
        $top = _("DONE");
        $bottom = "(<b>"._("My Recently Completed")."</b> - "._("pages I've finished proofreading, that are still available for correction)");
        $state_condition = "state='{$round->page_save_state}'";
        $bg_color = '99FF66';
    }
    else
    {
        $top = _("IN PROGRESS");
        $bottom = "(<b>"._("My Recently Proofread")."</b> - "._("pages I haven't yet completed)");
        $state_condition = "(state='{$round->page_temp_state}' OR state='{$round->page_out_state}')";
        $bg_color = 'FFCC66';
    }

    echo_row_b( $top, $bottom, $bg_color );

    $recentNum=5;

    $sql = "
        SELECT image, fileid, state, {$round->time_column_name}
        FROM $projectid
        WHERE {$round->user_column_name}='$pguser' AND $state_condition
        ORDER BY {$round->time_column_name} DESC
        LIMIT $recentNum
    ";
    $result = mysql_query($sql);
    $rownum = 0;
    $numrows = mysql_num_rows($result);

    while (($rownum < $recentNum) && ($rownum < $numrows))
    {
        $imagefile = mysql_result($result, $rownum, "image");
        $fileid = mysql_result($result, $rownum, "fileid");
        $timestamp = mysql_result($result, $rownum, $round->time_column_name);
        $pagestate = mysql_result($result, $rownum, "state");
        $newproject = "project=$projectid";
        $newfileid="&amp;fileid=$fileid";
        $newimagefile = '&amp;imagefile='.$imagefile;
        $newproofstate = '&amp;proofstate='.$state;
        $newpagestate = '&amp;pagestate='.$pagestate;
        $saved="&amp;saved=1";
        $editone="&amp;editone=1";
        if (($rownum % 5) ==0) {echo "</tr><tr>";}
        $eURL="$code_url/tools/proofers/proof.php?".$newproject.$newfileid.$newimagefile.$newproofstate.$newpagestate.$saved.$editone;
        echo "<TD ALIGN=\"center\">";
        echo "<A HREF=\"$eURL\">";
        echo strftime(_("%b %d"), $timestamp).": ".$imagefile."</a></td>\r\n";
        $rownum++;
    }

    while (($rownum % 5) !=0) {echo "<td>&nbsp;</td>"; $rownum++;}
    echo "</tr>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_edit_above()
{
    global $project, $code_url;
    if (!$project->can_be_managed_by_current_user) return;

    echo "<p>";
    echo "<a href='$code_url/tools/project_manager/editproject.php?action=edit&project=$project->projectid'>";
    echo _("Edit the above information");
    echo "</a>";
    echo "</p>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_early_uploads()
{
    global $project, $code_url, $uploads_account, $pguser;
    if (!$project->can_be_managed_by_current_user) return;

    $projectid = $project->projectid;
    $state = $project->state;

    $add_reminder = FALSE;

    $user_dir = str_replace( ' ', '_', $pguser );

    // Load TP&V images
    global $site_supports_metadata;
    if ($site_supports_metadata)
    {
        if ($state == PROJ_NEW)
        {
            echo "<br>\n";
            echo "<form method='get' action='$code_url/tools/project_manager/add_files.php'>\n";
            echo "<input type='hidden' name='project' value='$projectid'>\n";
            echo "<input type='hidden' name='tpnv' value='1'>\n";
            echo "<b>", _("Add title page and verso from directory or zip file:"), "</b>";
            echo "<br>\n";
            $initial_rel_source = "$user_dir/";
            echo "~$uploads_account/ <input type='text' name='rel_source' size='50' value='$initial_rel_source'>";
            echo "<br>\n";
            echo "<input type='submit' value='Add'>";
            echo "<br>\n";
            echo "</form>\n";
            $add_reminder = TRUE;
        }
    }

    // Load text+images from uploads area into project.
    if (
        ($state == PROJ_NEW && ! $site_supports_metadata)
        || ( $site_supports_metadata && ($state == PROJ_NEW_APPROVED || $state == PROJ_NEW_FILE_UPLOADED) )
        || $state == PROJ_P1_UNAVAILABLE
    )
    {
        echo "<br>\n";
        echo "<form method='get' action='$code_url/tools/project_manager/add_files.php'>\n";
        echo "<input type='hidden' name='project' value='$projectid'>\n";
            echo _("Add/Replace text and images from directory or zip file:");
            echo "<br>\n";
            $initial_rel_source = "$user_dir/";
            echo "~$uploads_account/ <input type='text' name='rel_source' size='50' value='$initial_rel_source'>";
        echo "<br>\n";
        echo "<input type='submit' value='Add/Replace'>";
        echo "<br>\n";
        echo "</form>\n";
        $add_reminder = TRUE;
    }

    if ($add_reminder)
    {
        // remind where/how to ftp projects.
        global $uploads_host,$uploads_account,$uploads_password;
        echo "<p>";
        echo sprintf(
            _("Reminder for uploads: host=<b>%s</b> account=<b>%s</b> password=<i><font color='#DDD'>%s</font></i>"),
            $uploads_host, $uploads_account, $uploads_password );
        echo "</p>";
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_images()
{
    global $project;
    global $code_url;

    if ( !$project->dir_exists ) return;

    $projectid = $project->projectid;

    echo "<h4>", _('Images'), "</h4>";
    echo "<ul>";

    echo "<li>";
    echo "<a href='$code_url/tools/proofers/images_index.php?project=$projectid'>";
    echo _("View Images Online");
    echo "</a>";
    echo "</li>\n";

    echo "</ul>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_extra_files()
{
    global $project;

    if ( !$project->can_be_managed_by_current_user
        && !$project->PPer_is_current_user )
    {
        return;
    }

    if ( !$project->dir_exists ) return;

    echo "<h4>", _('Extra Files in Project Directory'), "</h4>";

    $saved_dir = getcwd();

    chdir($project->dir);
    $filenames = glob("*" );

    echo "<ul>";

    if ( count($filenames) == 0 )
    {
        echo "<li>(none)</li>\n";
    }
    else
    {
        $res = mysql_query("
            SELECT image
            FROM $project->projectid
        ") or die(mysql_error());
        $excluded_filenames = array();
        while ( list($excluded_filename) = mysql_fetch_row($res) )
        {
                $excluded_filenames[$excluded_filename] = 1;
        }
        // These three appear under "Post Downloads":
        $excluded_filenames[$project->projectid . 'images.zip'] = 1;
        $excluded_filenames[$project->projectid . '.zip'] = 1;
        $excluded_filenames[$project->projectid . '_TEI.zip'] = 1;

        foreach ($filenames as $filename)
        {
            if ( !array_key_exists( $filename, $excluded_filenames ) )
            {
                echo "<li><a href='$project->url/$filename'>$filename</a></li>";
            }
        }
    }

    echo "</ul>";

    chdir($saved_dir);
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_post_downloads()
{
    global $project, $pguser;

    if ( !user_can_work_in_stage($pguser, 'PP') ) return;

    if ( !$project->dir_exists && !$project->pages_table_exists ) return;

    $projectid = $project->projectid;
    $state = $project->state;

    echo "<h4>";
    echo _("Post Downloads");
    echo "</h4>\n";

    echo "<ul>";

    echo_download_zip( _("Download Zipped Images"), 'images' );

    if ($state==PROJ_POST_FIRST_AVAILABLE || $state==PROJ_POST_FIRST_CHECKED_OUT)
    {
        echo_download_zip( _("Download Zipped Text"), '' );

        echo_download_zip( _("Download Zipped TEI Text"), '_TEI' );
    }
    elseif ($state==PROJ_POST_SECOND_AVAILABLE || $state==PROJ_POST_SECOND_CHECKED_OUT)
    {
        echo_download_zip( _("Download Zipped Text"), '_second' );
    }
    elseif ($state==PROJ_CORRECT_AVAILABLE || $state==PROJ_CORRECT_CHECKED_OUT)
    {
        echo_download_zip( _("Download Zipped Text"), '_corrections' );
    }

    if ( user_is_a_sitemanager() )
    {
        global $Round_for_round_id_, $code_url;

        $sums_str = "";
        foreach ( $Round_for_round_id_ as $round )
        {
            if ( !empty($sums_str) ) $sums_str .= ', ';
            $sums_str .= "SUM( $round->text_column_name != '' ) AS $round->id";
        }
        $res = mysql_query("
            SELECT $sums_str
            FROM $projectid
        ") or die(mysql_error());
        $sums = mysql_fetch_assoc($res);

        foreach ( $Round_for_round_id_ as $round )
        {
            if ( intval($sums[$round->id]) != 0 )
            {
                $highest_round_id = $round->id;
            }
        }

        echo "<br>";
        echo "<li>";
        echo "Generate Post Files (This will overwrite existing post files, if any.)\n";
        echo "<form method='post' action='$code_url/tools/project_manager/generate_post_files.php'>\n";
        echo "<input type='hidden' name='projectid' value='$projectid'>\n";

        echo "<input type='radio' name='round_id' value='[OCR]'>[OCR]&nbsp;\n";
        if ( isset($highest_round_id) )
        {
            foreach ( $Round_for_round_id_ as $round )
            {
                $checked = ( $round->id == $highest_round_id ? 'CHECKED' : '');
                echo "<input type='radio' name='round_id' value='$round->id' $checked>$round->id&nbsp;\n";
                if ( $round->id == $highest_round_id ) break;
            }
        }
        echo "<br>";
        echo "<input type='submit' value='(Re)generate'>\n";
        echo "</form>\n";
        echo "</li>";
    }

    echo "</ul>\n";
}

// -----------------------------------------------------------------------------

function echo_download_zip( $link_text, $discriminator )
{
    global $project, $code_url;

    $projectid = $project->projectid;
    if ( $discriminator == 'images' )
    {
        // Generate images zip on the fly,
        // so it's not taking up space on the disk.

        $url = "$code_url/tools/download_images.php?projectid=$projectid&amp;dummy={$projectid}images.zip";
        // The 'dummy' parameter is for the benefit of download-software that
        // names the resulting file after the last component of the request URL.

        // Images don't compress much in a zip,
        // so the sum of their individual filesizes
        // is a fair approximation (and hopefully an upper bound)
        // of the size of the resulting zip.
        $filesize_b = 0;
        foreach( glob("$project->dir/*.{png,jpg}", GLOB_BRACE) as $image_path )
        {
            $filesize_b += filesize($image_path);
        }
        $filesize_kb = round( $filesize_b / 1024 );
    }
    else
    {
        $p = "$projectid$discriminator.zip";

        $url = "$project->url/$p";
        $filesize_kb = round( filesize( "$project->dir/$p") / 1024 );
    }

    echo "<li>";
    echo "<a href='$url'>";
    echo $link_text;
    echo "</a>";
    echo " ($filesize_kb kb)";
    echo "</li>";
    echo "\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_postcomments()
{
    global $project, $code_url, $forums_url;

    if ( $project->state != PROJ_POST_FIRST_CHECKED_OUT ) return;

    $projectid = $project->projectid;

    if ($project->PPer_is_current_user)
    {

      echo "<h4>" . _("Post-Processor's Comments") . "</h4>";

      // Give the PP-er a chance to update the project record
      // (limit of 90 days is mentioned below).
      echo '<p>' . sprintf(_("You can use this text area to enter comments on how you're
                     doing with the post-processing, both to keep track for yourself
                     and so that we will know that there's still work in progress.
                     You will not recieve an e-mail reminder about this project for at
                     least another %1\$d days.") .
                     _("You can use this feature to keep track of your progress,
                     missing pages, etc. (if you are waiting on missing images or page
                     scans, please add the details to the <a href='%2\$s'>Missing Page
                     Wiki</a>)."),
                     90, "$forums_url/viewtopic.php?t=7584") . ' ' .
                     _("Note that your old comments will be replaced by those you enter here.") . '</p>';

      echo "<form name='pp_update' method='post' action='$code_url/tools/post_proofers/postcomments.php'>\n";
      echo "<textarea name='postcomments' cols='60' rows='6'>\n";
      echo htmlspecialchars($project->postcomments);
      echo "</textarea>\n";
      echo "<input type='hidden' name='projectid' value='$projectid' />\n";
      echo "<br /><input type='submit' value='" . _('Update comment and project status') . "'/>";
      echo "</form>\n";

    }

}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_smooth_reading()
{
    global $project, $code_url;

    if ( $project->state != PROJ_POST_FIRST_CHECKED_OUT ) return;

    $projectid = $project->projectid;

    echo "<h4>", _('Smooth Reading'), "</h4>";
    echo "<ul>";

    if ( $project->smoothread_deadline == 0 )
    {
        echo "<li>";
        echo _('This project has not been made available for smooth reading.');
        echo "</li>";

        if ($project->PPer_is_current_user)
        {
            echo "<li>";
            echo _("But as the project's PPer, you can make it available.");
            echo _('Choose how long you want to make it available for.');
            $link_start = "<a href='$code_url/tools/upload_text.php?project=$projectid&stage=smooth_avail&weeks";
            echo "<ul>";
            echo "<li>$link_start=1'>"._("one week")."</a>";
            echo "<li>$link_start=2'>"._("two weeks")."</a>";
            echo "<li>$link_start=4'>"._("four weeks")."</a>";
            echo "</ul>";
            echo "</li>\n";
        }

    }
    else
    {
        // Project has been made available for SR

        if ( time() < $project->smoothread_deadline )
        {
            $sr_deadline_str = strftime(
                _("%A, %B %e, %Y"), $project->smoothread_deadline );
            $sr_sentence = sprintf(
                _('This project has been made available for smooth reading until %s.'),
                "<b>$sr_deadline_str</b>"
            );

            echo "<li>";
            echo $sr_sentence;
            echo "</li>\n";

            if (!$project->PPer_is_current_user)
            {
                echo "<li>";
                echo "<a href='$project->url/{$projectid}_smooth_avail.zip'>";
                echo _('Download zipped text for smooth reading');
                echo "</a>";
                echo "</li>\n";

                echo "<li>";
                echo "<a href='$code_url/tools/upload_text.php?project=$projectid&stage=smooth_done'>";
                echo _("Upload a smooth-read text") ;
                echo "</a>";
                echo "</li>\n";
                // The upload does not cause the project to change state --
                // it's still checked out to PPer.
            }
            else
            {
                echo "<li>";
                echo "<a href='$code_url/tools/upload_text.php?project=$projectid&stage=smooth_avail&weeks=replace'>";
                echo _("Replace the current file that's available for smooth-reading.");
                echo "</a>";
                echo "</li>";
            }
        }
        else
        {
            echo "<li>";
            echo _('The deadline for smooth-reading this project has passed.');
            echo "</li>";

            if ($project->PPer_is_current_user)
            {
                echo "<li>";
                echo _("But as the project's PPer, you can make it available for smooth-reading for a further period.")." ";
                echo _('Choose how long you want to make it available for.');
                $link_start = "<a href='$code_url/tools/upload_text.php?project=$projectid&stage=smooth_avail&weeks";
                echo "<ul>";
                echo "<li>$link_start=1'>"._("one week")."</a>";
                echo "<li>$link_start=2'>"._("two weeks")."</a>";
                echo "<li>$link_start=4'>"._("four weeks")."</a>";
                echo "</ul>";
                echo "</li>\n";
            }


        }

        if ($project->PPer_is_current_user)
        {
            echo "<li>";
            $done_files = glob("$project->dir/*smooth_done_*.zip");
            if ($done_files)
            {
                echo _("Download smoothread file uploaded by:");
                echo "<ul>";
                foreach ($done_files as $filename)
                {
                    $showname = basename($filename,".zip");
                    $showname = substr($showname, strpos($showname,"_done_") + 6);
                    echo_download_zip( $showname, '_smooth_done_'.$showname );
                }
                echo "</ul>";
            }
            else
            {
                echo _("No smooth-read results have been uploaded.");
            }
            echo "</li>";
        }
    }

    echo "</ul>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_change_state()
{
    global $project, $pguser;

    $valid_transitions = get_valid_transitions( $project, $pguser );
    if (count($valid_transitions) == 0 ) return;

    echo "<h4>";
    echo _("Change Project State");
    echo "</h4>\n";

    foreach ( $valid_transitions as $transition )
    {
        echo_option(
            $transition->next_state,
            $transition->action_name,
            $transition->confirmation_question
        );
    }
}

function echo_option($code,$label,$question)
{
    global $project, $code_url;

    $projectid = $project->projectid;
    $state = $project->state;

    echo "<form method='get' action='$code_url/tools/changestate.php'>";
    echo "<input type='hidden' name='projectid' value='$projectid'>\n";
    echo "<input type='hidden' name='curr_state' value='$state'>\n";
    echo "<input type='hidden' name='next_state' value='$code'>\n";

    if ( is_null($question) )
    {
        $onClick_condition = "return true;";
    }
    else
    {
        $onClick_condition = "return confirm(\"$question\");";
    }
    $onclick_attr = "onClick='$onClick_condition'";
    echo "<input type='submit' value='$label' $onclick_attr>";

    echo "</form>\n";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_page_summary()
{
    global $project;
    $projectid = $project->projectid;

    if ( !$project->pages_table_exists ) return;

    echo "<center>";
    echo "<h3>"._("Page Summary")."</h3>\n";

    // page counts by state.
    $res = mysql_query( "SELECT count(*) AS total_num_pages FROM $projectid" );
    $total_num_pages = mysql_result($res,0,'total_num_pages');

    // This could be made faster (by doing one SQL query outside the loop)
    // but I'm not sure the savings would be noticeable.
    echo "<table border=0>\n";
    global $PAGE_STATES_IN_ORDER;
    foreach ($PAGE_STATES_IN_ORDER as $page_state)
    {
        $res = mysql_query( "
            SELECT count(*) AS num_pages
            FROM $projectid
            WHERE state='$page_state'
        ");
        $num_pages = mysql_result($res,0,'num_pages');
        if ( $num_pages != 0 )
        {
            echo "<tr><td align='right'>$num_pages</td><td>".sprintf(_("in %s"),$page_state)."</td></tr>\n";
        }
    }
    echo "<tr><td colspan='2'><hr></td></tr>\n";
    echo "<tr><td align='right'>$total_num_pages</td><td align='center'>"._("pages total")."</td></tr>\n";
    echo "</table>\n";
    echo "</center>";
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

function do_page_table()
{
    global $project;

    if ( !$project->pages_table_exists ) return;

    {
        global $relPath;
        include($relPath.'../tools/project_manager/detail_legend.inc');
        echo _("N.B. It is <b>strongly</b> recommended that you view page differentials by right-clicking on a diff link and opening the link in a new window or tab.")."<br>\n";

        // second arg. indicates to show size of image files.
        echo_page_table($project, 1);
    }
}

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// vim: sw=4 ts=4 expandtab
?>
