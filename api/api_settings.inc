<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');

function settings($action)
{
    // temporarily use a session variable
    $default = '{"split":1}';
    switch($action) {
    case 'get':
        if(!isset($_SESSION["proof_style"]))
        {
            $_SESSION["proof_style"] = $default;
        }
        // this gets immediately re-encoded
//        return json_decode($_SESSION["proof_style"]);
        return array("settings" => $_SESSION["proof_style"]);
    case 'put':
        $_SESSION["proof_style"] = array_get($_REQUEST, 'data', $default);
        break;
    }
}

// vim: sw=4 ts=4 expandtab
