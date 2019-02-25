<?php
include_once('api_keydata.inc');
include_once('api_config_strings.inc');

// DP API v1
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
