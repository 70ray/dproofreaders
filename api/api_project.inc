<?php
include_once($relPath.'SettingsClass.inc'); // get_Settings()
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
    $userSettings =& Settings::get_Settings($pguser);
    $user_set = $userSettings->get_value("pickerset", "");
    $user_set = mysqli_real_escape_string(DPDatabase::get_connection(), $user_set);
    $user_set = explode(" ", $user_set);

    $picker_set = array_unique(array_merge($picker_set, $user_set));
    $pickers = get_pickers($picker_set);
    maybe_utf8_encode_array($pickers);
    return ['pickers' => $pickers];
}
