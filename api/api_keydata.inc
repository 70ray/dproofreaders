<?php
include_once($relPath.'misc.inc'); // array_get() surround_and_join()
include_once($relPath.'api_utils.inc'); // check_login()

function pickers($action)
{
    check_login();
    switch($action)
    {
    case 'list':
        // return array of all codes
        $codes = []; // initialise to empty array in case there is no data
        $result = mysqli_query(DPDatabase::get_connection(), "SELECT code FROM character_pickers");
        while($row = mysqli_fetch_assoc($result))
        {
            $codes[] = $row["code"];
        }
        return ['codes' => $codes];
    }
}

// return pickers array from set of codes
function get_pickers($picker_set)
{
    $picker_set = surround_and_join($picker_set, "'", "'", "," );
    // now looks like 'A', 'E', 'U'
    $result = mysqli_query(DPDatabase::get_connection(), "
        SELECT * FROM character_pickers
        WHERE code IN ($picker_set)
        ORDER BY FIELD(code, $picker_set)");
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// actions for an single picker
function get_picker()
{
    check_login();
    $code = array_get($_REQUEST, 'code', '');
    $code = mysqli_real_escape_string(DPDatabase::get_connection(), $code);
    $result = mysqli_query(DPDatabase::get_connection(), "SELECT * FROM character_pickers WHERE code='$code'");
    $picker = mysqli_fetch_assoc($result);
    return ['picker' => $picker];
}

function put_picker($action)
{
    check_login();
    // should check if user is allowed to do this
    switch($action)
    {
    case 'delete':
        $code = array_get($_REQUEST, 'code', '');
        $code = mysqli_real_escape_string(DPDatabase::get_connection(), $code);
        mysqli_query(DPDatabase::get_connection(), "DELETE FROM character_pickers WHERE code='$code'");
        break;
    case 'insert':
        // will throw exception if duplicate code
        ins_rep("INSERT");
        break;
    case 'replace':
        ins_rep("REPLACE");
        break;
    }
}

// insert or replace
function ins_rep($method)
{
    // make an array rather than an object
    $new_picker = json_decode(array_get($_REQUEST, 'data', ""), true);
    $link = DPDatabase::get_connection();
    $code = mysqli_real_escape_string($link, $new_picker['code']);
    if("" == $code)
    {
        throw new Exception(_("The code should not be empty"));
    }
    if(strpos($code, " ") !== FALSE)
    {
        throw new Exception(_("The code should not contain a space character"));
    }
    $upper = mysqli_real_escape_string($link, $new_picker['upper']);
    $lower = mysqli_real_escape_string($link, $new_picker['lower']);
    mysqli_query(DPDatabase::get_connection(), "
        $method INTO character_pickers
        SET
            code='$code',
            upper='$upper',
            lower='$lower'
    ");
}
