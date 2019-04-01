<?php
include_once('api_user_settings.inc'); // get_user_string()
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

    // merge in the project set not yet done

    // get the user's set
    $user_set = get_user_string("pickerset");
    $user_set = mysqli_real_escape_string(DPDatabase::get_connection(), $user_set);
    $user_set = explode(" ", $user_set);

    $picker_set = array_unique(array_merge($picker_set, $user_set));
    $pickers = get_pickers($picker_set);
    return ['pickers' => $pickers];
}
