<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'site_vars.php'); // $utf8_site
include_once($relPath.'misc.inc'); // html_safe()
include_once($relPath.'Project.inc');
include_once($relPath.'project_states.inc'); // project_states_text()
include_once($relPath.'LPage.inc');
include_once($relPath.'links.inc'); // private_message_link()
include_once($relPath.'forum_interface.inc'); // get_forum_user_id()
include_once('api_keydata.inc');

function project($project_id, $action)
{
    switch($action)
    {
    case 'listdata':
        $fields = array_get($_GET, 'fields', null);
        if(!$fields)
        {
            $fields = ['nameofwork', 'projectid'];
        }
        return array('project' => get_project_data($project_id, $fields));
    case 'listpages':
        $fields = array_get($_GET, 'fields', null);
        if(!$fields)
        {
            $fields = ['fileid', 'image'];
        }
        return get_project_pages($project_id, $fields);
    case 'langdata':
        return get_langdata($project_id);
    default:
        break;
    }
}

function get_project_pages($projectid, $fields)
{
    $query_fields = implode(', ', $fields);
    $sql = "SELECT $query_fields FROM $projectid";
    $res = try_query($sql);
    return array('pages' => mysqli_fetch_all($res, MYSQLI_ASSOC));
}

class ProjectPage
{
    function __construct($projectID, $proj_state, $imageID, $page_state)
    {
        $this->lpage = get_indicated_LPage($projectID, $proj_state, $imageID, $page_state);
    }

    const TEXT = 1;
    const INFO = 2;

    // send necessary data, doesn't matter if we send too much unless expensive to construct

    function page_data($dataset)
    {
        global $utf8_site;

        $data = array();
        if($dataset & self::TEXT)
        {
            $text = $this->lpage->get_text();
            if(!$utf8_site)
            {
                $text = utf8_encode($text);
            }
            $data['text'] = $text;
            $data['imageID'] = $this->lpage->imagefile;
        }
        if($dataset & self::INFO)
        {
            $data['isFormattingRound'] = $this->lpage->is_formatting_round();
            $data['prevLinks'] = $this->p_links();
        }
        // always send this
        $data['pageState'] = $this->lpage->page_state;
        return $data;
    }

    function return_data($action)
    {
        global $pguser, $utf8_site;

        $text_data = array_get($_POST, 'text-data', '');
        if(!$utf8_site)
        {
            $text_data = utf8_decode($text_data);
        }

        switch($action) {
        case 'checkoutpage':
            $this->lpage->resume_saved_page($pguser);
            return $this->page_data(self::TEXT + self::INFO); // send everything
            break;
        case 'returnpage':
            $this->lpage->returnToRound($pguser);
            break;
        case 'reverttoorig':
            $this->lpage->saveAsInProgress($text_data, $pguser);
            $this->lpage->revertToOriginal();
            return $this->page_data(self::TEXT); // send text and state
        case 'reverttolastsave':
            $this->lpage->revertToSaved();
            return $this->page_data(self::TEXT);
        case 'saveasinprogress':
            $this->lpage->saveAsInProgress($text_data, $pguser);
            return $this->page_data(0); // just state
        case 'saveasdone':
            $this->lpage->saveAsDone($text_data, $pguser);
            break;
        case 'saveanddonext':
            $this->lpage->saveAsDone($text_data, $pguser);
            $this->lpage = get_available_page($project_id, $proj_state, $pguser);
            return $this->page_data(self::TEXT + self::INFO);
        case 'reportbad':
            $reason = array_get($_REQUEST, 'reason', 0);
            $project_bad = $this->lpage->markAsBad($pguser, $reason);
            return array('projectBad' => $project_bad);
        default:
            throw new Exception("$action not implemented");
            break;
        }
    }

    function p_links()
    {
        $round = $this->lpage->round;
        $other_round_ids = $round->other_rounds_with_visible_usernames;
        $round_links = array();
        foreach ($other_round_ids as $other_round_id)
        {
            $r_string = '';
            $other_round = get_Round_for_round_id($other_round_id);
            $username = $this->lpage->get_username_for_round( $other_round );

            $r_string .= "$other_round->id: ";
            // if no forum_user_id can't send message
            $user_id = get_forum_user_id($username);
            if ( $username == '' || $user_id == NULL )
            {
                // Grey, because it's not the link expected by the user.
                $r_string .= "<span style='color: #666;'>";
                if ($username == '')
                {
                    // e.g., the project might have skipped $other_round,
                    // or it could be a future round.
                    $r_string .= "<i>" . pgettext("no user", "none") . "</i>";
                }
                else
                    $r_string .= $username;
                $r_string .= "</span>";
            }
            else
            {
                $r_string .= private_message_link($username, "comments");
            }
            $round_links[] = $r_string;
        }
        return implode(", ", $round_links);
    }
}

function project_state_action($project_id, $proj_state, $action)
{
    global $pguser;

    switch ($action)
    {
    // returns page index (image file name) and changes page state to out
    // could be a stale link so validate
    case 'checkoutnextpage':
        $lpage = get_available_page($project_id, $proj_state, $pguser);
        return array('imageID' => $lpage->imagefile, 'pageState' => $lpage->page_state);
    }
}

// vim: sw=4 ts=4 expandtab
