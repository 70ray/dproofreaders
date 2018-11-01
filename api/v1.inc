<?php
include_once('api_project.inc');
include_once('api_settings.inc');
include_once('api_constants.inc');
// DP API v1
function v1_project_action($params)
{
    $projectID = $params[0];
    $action = $params[1];
    return project($projectID, $action);
}

// post because not idempotent - checks out a new page every time
function v1_project_state_action($params)
{
    $project_id = $params[0];
    $proj_state = $params[1];
    $action = $params[2];
    return project_state_action($project_id, $proj_state, $action);
}

function v1_project_state_page_state_action($params)
{
    $projectID = $params[0];
    $proj_state = $params[1];
    $imageID = $params[2];
    $page_state = $params[3];
    $action = $params[4];
    $project_page = new ProjectPage($projectID, $proj_state, $imageID, $page_state);
    return $project_page->return_data($action);
//    return project_page_action($projectID, $proj_state, $imageID, $page_state, $action);
}

function v1_settings($params)
{
    $action = $params[0];
    return settings($action);
}

function v1_constants($params)
{
    return constants($params[0]);
}
