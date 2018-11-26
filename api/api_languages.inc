<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'languages.inc');

function languages()
{
    global $utf8_site;

    $languages = array_values(get_languages_with_dictionaries());
    sort($languages);
    if(!$utf8_site)
    {
        array_walk($languages, function(&$item) { $item = utf8_encode($item);} );
    }
    return array('languages' => $languages);
}

// vim: sw=4 ts=4 expandtab
