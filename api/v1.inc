<?php
include_once('api_project.inc');
// DP API v1
function v1_project_GET($params)
{
    $projectID = $params[0];
    return get_project_data($projectID);
}

function v1_project_pages_GET($params)
{
    $projectID = $params[0];
    $fields = array_get($_GET, 'fields', null);
    return get_project_pages($projectID, $fields);
}

// post because not idempotent - checks out a new page every time
function v1_project_checkoutnextpage_POST($params)
{
    $projectID = $params[0];
    $state = $params[1];
    return project_checkoutnextpage($projectID, $state);
}

function v1_project_checkoutpage_GET($params)
{
    $projectID = $params[0];
    $imageID = $params[1];
    $proj_state = array_get($_GET, 'proj-state', null);
    $page_state = array_get($_GET, 'page-state', null);
    return project_checkoutpage($projectID, $proj_state, $imageID, $page_state);
}

function v1_project_returnpage_GET($params)
{
    $projectID = $params[0];
    $imageID = $params[1];
    $proj_state = array_get($_GET, 'proj-state', null);
    $page_state = array_get($_GET, 'page-state', null);
    return project_returnpage($projectID, $proj_state, $imageID, $page_state);
}