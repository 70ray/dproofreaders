<?php
include_once('api_config_strings.inc'); // get_config_string()
include_once('api_keydata.inc'); // get_pickers()

// get the set of pickers default + project
function project_picker($projectID)
{
    global $pguser;

    check_login();

    // get the default set
    $picker_code_string = get_config_string("pickerset");
    $picker_code_string = mysqli_real_escape_string(DPDatabase::get_connection(), $picker_code_string);
    $picker_set = explode(" ", $picker_code_string);

    // merge in the project set
    $proj_code_string = get_project_picker_string($projectID);
    $proj_code_string = mysqli_real_escape_string(DPDatabase::get_connection(), $proj_code_string);
    $proj_set = explode(" ", $proj_code_string);
    $picker_set = array_unique(array_merge($picker_set, $proj_set));
    
    $picker_codes = surround_and_join($picker_set, "'", "'", "," );
    // now looks like 'A', 'E', 'U'
    $result = mysqli_query(DPDatabase::get_connection(), "
        SELECT * FROM character_pickers
        WHERE code IN ($picker_codes)
        ORDER BY FIELD(code, $picker_codes)");
    return ['pickers' => mysqli_fetch_all($result, MYSQLI_ASSOC)];
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
