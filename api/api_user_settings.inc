<?php
include_once($relPath.'misc.inc'); // array_get()
include_once($relPath.'utf8_utils.inc'); // maybe_utf8_encode() maybe_utf8_decode()
include_once($relPath.'api_utils.inc'); // check_login()
include_once($relPath.'SettingsClass.inc'); // get_Settings()

function get_user_setting($id)
{
    global $pguser;
    check_login();

    $userSettings =& Settings::get_Settings($pguser);
    $user_set = $userSettings->get_value($id, "");
    return ['string' => maybe_utf8_encode($user_set)];
}

function put_user_setting($id)
{
    global $pguser;
    check_login();

    $data = maybe_utf8_decode(array_get($_REQUEST, 'data', ""));
    $userSettings =& Settings::get_Settings($pguser);
    $userSettings->set_value($id, $data);
}
