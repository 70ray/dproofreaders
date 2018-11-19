<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');

$key_titles = [
    '¶' => _('pilcrow'),
    '°' => _('degree'),
    'º' => _('masculine ordinal'),
    '·' => _('mid-dot'),
];

if(!$utf8_site)
{
    array_walk($key_titles, function(&$item) { $item = utf8_encode($item);} );
}
echo "var keyTitles = ",  json_encode($key_titles), ";\n";
