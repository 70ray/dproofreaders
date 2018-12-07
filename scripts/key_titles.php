<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'utils.inc');

$key_titles = [
    '¶' => _('pilcrow'),
    '°' => _('degree'),
    'º' => _('masculine ordinal'),
    '·' => _('mid-dot'),
];

maybe_encode_array($key_titles);
echo "var keyTitles = ",  json_encode($key_titles), ";\n";
