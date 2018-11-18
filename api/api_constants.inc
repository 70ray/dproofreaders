<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'DPage.inc');

function constants($type)
{
    global $utf8_site, $PAGE_BADNESS_REASONS;

    switch($type) {
    case 'page_badness_reasons':
        $data = $PAGE_BADNESS_REASONS;
        if(!$utf8_site)
        {
            array_walk($data, function(&$item) { $item = utf8_encode($item);} );
        }
        return array('reasons' => $data);
    default:
        break;
    }
}

// vim: sw=4 ts=4 expandtab
