<?php
include_once('api_config_strings.inc'); // get_config_string()
include_once('api_keydata.inc'); // get_pickers()

function project_picker($projectID)
{
    global $pguser;

    check_login();

    // get the default set
    $picker_set = get_config_string("pickerset");
    $picker_set = mysqli_real_escape_string(DPDatabase::get_connection(), $picker_set);
    $picker_set = explode(" ", $picker_set);

    // merge in the project set
    $proj_set = get_project_picker_string($projectID);
    $proj_set = mysqli_real_escape_string(DPDatabase::get_connection(), $proj_set);
    $proj_set = explode(" ", $proj_set);
    $picker_set = array_unique(array_merge($picker_set, $proj_set));

    $pickers = get_pickers($picker_set);
    return ['pickers' => $pickers];
}

function get_project_pickerset($projectID)
{
    check_login();
    return ['string' => get_project_picker_string($projectID)];
}

function put_project_pickerset($projectID)
{
    check_login();
    // should check if user is allowed to do this
    $data = array_get($_REQUEST, 'data', "");
    $data = mysqli_real_escape_string(DPDatabase::get_connection(), $data);
    mysqli_query(DPDatabase::get_connection(), "
        REPLACE INTO project_pickers
        SET
            projectid = '$projectID',
            pickerstring = '$data'
    ");
}

function get_project_picker_string($projectID)
{
    $result = mysqli_query(DPDatabase::get_connection(), "SELECT pickerstring FROM project_pickers WHERE projectid='$projectID'");
    return mysqli_fetch_assoc($result)["pickerstring"];
}
