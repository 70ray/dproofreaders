<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'site_vars.php'); // $utf8_site
include_once($relPath.'misc.inc'); // html_safe()
include_once($relPath.'Project.inc');
include_once($relPath.'project_states.inc'); // project_states_text()
include_once($relPath.'LPage.inc');
include_once('api_keydata.inc');

function project($project_id, $action)
{
    switch($action)
    {
    case 'listdata':
        return get_project_data($project_id);
    case 'listpages':
        $fields = array_get($_GET, 'fields', null);
        if(!$fields)
        {
            $fields = ['fileid', 'image'];
        }
        return get_project_pages($project_id, $fields);
    case 'keydata':
        return get_keydata();
    default:
        break;
    }
}

function page_data($lpage)
{
    global $utf8_site;

    $text = $lpage->get_text();
    if(!$utf8_site)
    {
        $text = utf8_encode($text);
    }
    return array('imageID' => $lpage->imagefile, 'pageState' => $lpage->page_state, 'text' => $text, 'isFormattingRound' => $lpage->is_formatting_round());
}

function project_state_action($project_id, $proj_state, $action)
{
    global $pguser;

    if('checkoutnextpage' == $action)
    {
        // returns page index (image file name) and changes page state to out
        // could be a stale link so validate
        $lpage = get_available_page($project_id, $proj_state, $pguser);
        return page_data($lpage);
    }
}

function project_page_action($project_id, $proj_state, $image_id, $page_state, $action)
{
    global $pguser, $utf8_site;

    $lpage = get_indicated_LPage($project_id, $proj_state, $image_id, $page_state, false);
    $text_data = array_get($_POST, 'text-data', '');
    if(!$utf8_site)
    {
        $text_data = utf8_decode($text_data);
    }

    switch($action) {
    case 'checkoutpage':
        $lpage->resume_saved_page($pguser);
        return page_data($lpage);
        break;
    case 'returnpage':
        $lpage->returnToRound($pguser);
        break;
    case 'reverttoorig':
//        $text_data = array_get($_POST, 'text-data', '');
        $lpage->saveAsInProgress($text_data, $pguser);
        $lpage->revertToOriginal();
        return page_data($lpage);
    case 'reverttolastsave':
        $lpage->revertToSaved();
        return page_data($lpage);
    case 'saveasinprogress':
//        $text_data = array_get($_POST, 'text-data', '');
        $lpage->saveAsInProgress($text_data, $pguser);
        return array('pageState' => $lpage->page_state);
    case 'saveasdone':
//        $text_data = array_get($_POST, 'text-data', '');
        $lpage->saveAsDone($text_data, $pguser);
        break;
    case 'saveanddonext':
//        $text_data = array_get($_POST, 'text-data', '');
        $lpage->saveAsDone($text_data, $pguser);
        $lpage = get_available_page($project_id, $proj_state, $pguser);
        return page_data($lpage);
    case 'reportbad':
        $reason   = array_get($_REQUEST, 'reason', 0);
        $project_bad = $lpage->markAsBad($pguser, $reason);
        return array('projectBad' => $project_bad);
    default:
        throw new Exception("$action not implemented");
        break;
    }
}

// vim: sw=4 ts=4 expandtab
