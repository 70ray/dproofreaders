<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'misc.inc'); // get_enumerated_param html_safe()

function get_project($projectid)
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
    $res = mysqli_query(DPDatabase::get_connection(), "
        SELECT *
        FROM projects
        WHERE projectid = '$projectid'");
    if(!$res)
    {
        $error = _("Database error1:") ." ". mysqli_error(DPDatabase::get_connection());
        throw new Exception($error);
    }
    $project = mysqli_fetch_assoc($res);
    if(!$project)
    {
        $error = sprintf(_("There is no project with projectid '%s'"), $projectid);
        throw new Exception($error);
    }
    return $project;
}

function get_project_pages($projectid, $fields)
{
    if(!$fields)
    {
        $fields = ['fileid', 'image'];
    }

    $query_fields = implode(', ', $fields);
    $res = mysqli_query(DPDatabase::get_connection(), "SELECT $query_fields FROM $projectid");
    if(!$res)
    {
        $error = _("Database error:") ." ". mysqli_error(DPDatabase::get_connection());
        throw new Exception($error);
    }
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

// vim: sw=4 ts=4 expandtab
