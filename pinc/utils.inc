<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');

function maybe_encode_array($the_array)
{
    global $utf8_site;

    if(!$utf8_site)
    {
        array_walk($the_array, function(&$item) { $item = utf8_encode($item);} );
    }
}

function maybe_decode_array($the_array)
{
    global $utf8_site;

    if(!$utf8_site)
    {
        array_walk($the_array, function(&$item) { $item = utf8_decode($item);} );
    }
}