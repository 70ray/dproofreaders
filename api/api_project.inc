<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc'); // html_safe()
include_once($relPath.'Project.inc');
include_once($relPath.'project_states.inc'); // project_states_text()
include_once($relPath.'LPage.inc');
include_once('errors.inc'); // throw_sql_error
include_once('api_page.inc');

function get_project_pages($projectid, $fields)
{
    if(!$fields)
    {
        $fields = ['fileid', 'image'];
    }

    $query_fields = implode(', ', $fields);
    $sql = "SELECT $query_fields FROM $projectid";
    $res = mysqli_query(DPDatabase::get_connection(), $sql);
    if(!$res)
    {
        throw_sql_error($sql);
    }
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function page_data($lpage)
{
    return array('imageID' => $lpage->imagefile, 'pageState' => $lpage->page_state, 'text' => $lpage->get_text());
}

function project_checkoutnextpage($projectid, $proj_state)
{
    global $pguser;

    // returns page index (image file name) and changes page state to out
    // could be a stale link so validate
    $lpage = get_available_page1($projectid, $proj_state, $pguser, $err);
    if($err)
    {
        throw new Exception($error);
    }
    return page_data($lpage);
}

function project_checkoutpage($projectid, $proj_state, $imageID, $page_state)
{
    global $pguser;

    $lpage = get_indicated_LPage1($projectid, $proj_state, $imageID, $page_state, false);
    $lpage->resume_saved_page($pguser);
    return page_data($lpage);
}

function project_returnpage($projectid, $proj_state, $imageID, $page_state)
{
    global $pguser;

    $lpage = get_indicated_LPage1($projectid, $proj_state, $imageID, $page_state, false);
    $lpage->returnToRound($pguser);
}

function project_revert_to_orig($projectid, $proj_state, $imageID, $page_state, $text_data)
{
    global $pguser;

    $lpage = get_indicated_LPage1($projectid, $proj_state, $imageID, $page_state, false);
    $lpage->saveAsInProgress($text_data, $pguser);
    $lpage->revertToOriginal();
    return page_data($lpage);
}

function project_save_as_in_progress($projectid, $proj_state, $imageID, $page_state, $text_data)
{
    global $pguser;

    $lpage = get_indicated_LPage1($projectid, $proj_state, $imageID, $page_state, false);
//throw new Exception("psaip2");
    $lpage->saveAsInProgress($text_data, $pguser);
    return array('pageState' => $lpage->page_state);
}

// vim: sw=4 ts=4 expandtab
