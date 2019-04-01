<?php
include_once($relPath.'misc.inc'); // array_get()
include_once($relPath.'api_utils.inc'); // check_login()
include_once($relPath.'SettingsClass.inc'); // get_Settings()

function get_user_setting($id)
{
    check_login();

    return ['string' => get_user_string($id)];
}

function put_user_setting($id)
{
    global $pguser;
    check_login();

    // encode with bin2hex so can have utf8 chars
    $data = bin2hex(array_get($_REQUEST, 'data', ""));
    $userSettings =& Settings::get_Settings($pguser);
    $userSettings->set_value($id, $data);
}

function get_user_string($id)
{
    global $pguser;

    $userSettings =& Settings::get_Settings($pguser);
    $user_string = $userSettings->get_value($id, "");
    return hex2bin($user_string);
}
