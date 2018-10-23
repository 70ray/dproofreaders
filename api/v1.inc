<?php
include_once('api_project.inc');
include_once('api_settings.inc');
// DP API v1
function v1_project($params)
{
    $projectID = $params[0];
    $action = $params[1];
    return project($projectID, $action);
}
/*
function v1_project_pages_GET($params)
{
    $projectID = $params[0];
    $fields = array_get($_GET, 'fields', null);
    return get_project_pages($projectID, $fields);
}*/

// post because not idempotent - checks out a new page every time
function v1_project_state($params)
{
    $project_id = $params[0];
    $proj_state = $params[1];
    $action = $params[2];
    return project_state_action($project_id, $proj_state, $action);
}

function v1_project_state_page_state($params)
{
    $projectID = $params[0];
    $proj_state = $params[1];
    $imageID = $params[2];
    $page_state = $params[3];
    $action = $params[4];
    return project_page_action($projectID, $proj_state, $imageID, $page_state, $action);
}

function v1_settings($params)
{
    $type = $params[0];
    $action = $params[1];
    return settings($type, $action);
}