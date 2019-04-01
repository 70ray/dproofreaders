<?php

function maybe_utf8_decode($text)
{
    global $utf8_site;

    if(!$utf8_site)
    {
        $text = utf8_decode($text);
    }
    return $text;
}

function maybe_utf8_encode($text)
{
    global $utf8_site;

    if(!$utf8_site)
    {
        $text = utf8_encode($text);
    }
    return $text;
}

function maybe_utf8_decode_array(&$the_array)
{
    global $utf8_site;

    if(!$utf8_site)
    {
        array_walk($the_array, function(&$item) { $item = utf8_decode($item);} );
    }
}

function maybe_utf8_encode_array(&$the_array)
{
    global $utf8_site;

    if(!$utf8_site)
    {
        array_walk_recursive($the_array, function(&$item) { $item = utf8_encode($item);} );
    }
}
