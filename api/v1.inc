<?php
include_once('api_project.inc');
// DP API v1
function v1_project_GET($params)
{
    $projectID = $params[0];
    return get_project($projectID);
}

function v1_project_pages_GET($params)
{
    $projectID = $params[0];
    $fields = array_get($_GET, 'fields', null);
    return get_project_pages($projectID, $fields);
}