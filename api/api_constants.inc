<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'DPage.inc');

function constants($type)
{
    global $PAGE_BADNESS_REASONS;

    switch($type) {
    case 'page_badness_reasons':
        return array('reasons' => $PAGE_BADNESS_REASONS);
    default:
        break;
    }
}

// vim: sw=4 ts=4 expandtab
