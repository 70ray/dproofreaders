<?php
$relPath="../pinc/";
include_once($relPath.'misc.inc');

$key_titles = [
    '¶' => _('pilcrow'),
    '°' => _('degree'),
    'º' => _('masculine ordinal'),
    '·' => _('mid-dot'),
];

function make_safe(&$item)
{
    $item = javascript_safe($item);
}
//array_walk($key_titles, 'make_safe');
echo "var keyTitles = ",  json_encode($key_titles, JSON_UNESCAPED_UNICODE), ";\n";
