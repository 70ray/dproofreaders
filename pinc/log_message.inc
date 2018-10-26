<?php

function log_message($msg)
{
    global $test_log;

    $test_log .= $msg;
}
