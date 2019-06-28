<?php
include_once('api_keydata.inc');
include_once('api_config_strings.inc');
include_once('api_project.inc');

// DP API v1
function v1_project_picker_GET($params)
{
    $projectID = $params[0];
    return project_picker($projectID);
}

function v1_project_pickerset_GET($params)
{
    $projectID = $params[0];
    return get_project_pickerset($projectID);
}

function v1_project_pickerset_POST($params)
{
    $projectID = $params[0];
    return put_project_pickerset($projectID);
}

function v1_pickers_GET($params)
{
    return pickers($params[0]);
}

function v1_picker_GET()
{
    return get_picker();
}

function v1_picker_POST($params)
{
    $action = $params[0];
    return put_picker($action);
}

function v1_config_GET($params)
{
    $id = $params[0];
    return get_config($id);
}

function v1_config_POST($params)
{
    $id = $params[0];
    return put_config($id);
}
