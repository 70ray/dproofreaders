<?php
include_once($relPath.'misc.inc'); // array_get()
include_once($relPath.'api_utils.inc'); // check_login()

function get_config($id)
{
    check_login();

    return ['string' => get_config_string($id)];
}

function put_config($id)
{
    check_login();
    // should check if user is allowed to do this
    $data = array_get($_REQUEST, 'data', "");
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
