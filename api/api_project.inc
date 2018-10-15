<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc'); // html_safe()
include_once($relPath.'Project.inc');
include_once($relPath.'project_states.inc'); // project_states_text()
include_once($relPath.'LPage.inc');
include_once('errors.inc'); // throw_sql_error

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
    $res = mysqli_query(DPDatabase::get_connection(), $sql);
    if(!$res)
    {
        throw_sql_error($sql);
    }
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

function project_checkoutnextpage($projectid, $proj_state)
{
    global $pguser;

    // returns page index (image file name) and changes page state to out
    // could be a stale link so validate
//throw new Exception("psc");
//    $project = new Project(get_project_data($projectid));
//    validate_state($project, $state);
    $lpage = get_available_page($projectid, $proj_state, $pguser, $err);
    if($err)
    {
        throw new Exception($error);
    }
    return $lpage->imagefile;
//    return $lpage;
}

function project_checkoutpage($projectid, $proj_state, $imagefile, $page_state)
{
    global $pguser;

    $lpage = get_indicated_LPage($projectid, $proj_state, $imagefile, $page_state, false);
    $lpage->resume_saved_page($pguser);
    return array('imageID' => $lpage->imagefile, 'text' => $lpage->get_text());
}

function project_returnpage($projectid, $proj_state, $imagefile, $page_state)
{
    global $pguser;

    $lpage = get_indicated_LPage($projectid, $proj_state, $imagefile, $page_state, false);
    $lpage->returnToRound($pguser);
}

/*
function validate_state($project, $expected_state)
{
//throw new Exception("vs");
    $expected_state_text = project_states_text($expected_state);
    if (empty($expected_state_text))
    {
        throw new Exception("parameter 'proj_state' has a bad value: '$expected_state'");
    }

    if ($expected_state != $project->state)
    {
        $error = sprintf(_('Warning: Project "%1$s" is no longer in state "%2$s"; it is now in state "%3$s".'),
            $project->nameofwork, $expected_state_text, project_states_text($project->state));
        throw new Exception($error);
    }
}
*/
// vim: sw=4 ts=4 expandtab
