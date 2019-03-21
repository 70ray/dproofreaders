<?php
include_once($relPath.'misc.inc'); // array_get() surround_and_join()
include_once($relPath.'utf8_utils.inc'); // maybe_utf8_encode_array() maybe_utf8_decode()
include_once($relPath.'api_utils.inc'); // check_login()

function pickers($action)
{
    check_login();
    switch($action)
    {
    case 'get':
        // return all (or a subset of) pickers
        $code_set = array_get($_REQUEST, 'code-set', null);
        if(!$code_set)
        {
            $where_clause = "";
            $order_clause = "";
        }
        else
        {
            $code_set = mysqli_real_escape_string(DPDatabase::get_connection(), $code_set);
            $code_set = explode(" ", $code_set);
            // $code_set is now an array of codes
            $code_set = surround_and_join($code_set, "'", "'", "," );
            // now looks like 'A', 'E', 'U'
            $code_set = maybe_utf8_decode($code_set);
            $where_clause = "WHERE code IN ($code_set)";
            $order_clause = "ORDER BY FIELD(code, $code_set)";
        }
        $result = mysqli_query(DPDatabase::get_connection(), "SELECT * FROM character_pickers $where_clause $order_clause");
        $pickers = mysqli_fetch_all($result, MYSQLI_ASSOC);
        maybe_utf8_encode_array($pickers);
        return ['pickers' => $pickers];

    case 'list':
        // return array of all codes
        $codes = []; // initialise to empty array in case there is no data
        $result = mysqli_query(DPDatabase::get_connection(), "SELECT code FROM character_pickers");
        while($row = mysqli_fetch_assoc($result))
        {
            $codes[] = $row["code"];
        }
        maybe_utf8_encode_array($codes);
        return ['codes' => $codes];
    }
}

// actions for an single picker
function get_picker()
{
    check_login();
    $code = maybe_utf8_decode(array_get($_REQUEST, 'code', ''));
    $code = mysqli_real_escape_string(DPDatabase::get_connection(), $code);
    $result = mysqli_query(DPDatabase::get_connection(), "SELECT * FROM character_pickers WHERE code='$code'");
    $picker = mysqli_fetch_assoc($result);
    maybe_utf8_encode_array($picker);
    return ['picker' => $picker];
}

function put_picker($action)
{
    check_login();
    switch($action)
    {
    case 'delete':
        $code = maybe_utf8_decode(array_get($_REQUEST, 'code', ''));
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
    maybe_utf8_decode_array($new_picker);
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
