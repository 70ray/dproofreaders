<?php
include_once($relPath.'misc.inc'); // array_get()
include_once($relPath.'utf8_utils.inc'); // maybe_utf8_encode() maybe_utf8_decode()
include_once($relPath.'api_utils.inc'); // check_login()

function get_config($id)
{
    check_login();

    return ['string' => maybe_utf8_encode(get_config_string($id))];
}

function put_config($id)
{
    check_login();
    // should check if user is allowed to do this
    $data = maybe_utf8_decode(array_get($_REQUEST, 'data', ""));
    $data = mysqli_real_escape_string(DPDatabase::get_connection(), $data);
    mysqli_query(DPDatabase::get_connection(), "
        REPLACE INTO configuration_strings
        SET
            id='$id',
            value='$data'
    ");
}

function get_config_string($id)
{
    $result = mysqli_query(DPDatabase::get_connection(), "SELECT value FROM configuration_strings WHERE id='$id'");
    return mysqli_fetch_assoc($result)["value"];
}
