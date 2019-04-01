<?php
include_once($relPath.'utf8_utils.inc');

// this file is encoded in utf8 so json_encode will work on keys
$key_titles = [
    '¶' => _('pilcrow'),
    'µ' => _('micro sign'),
    '°' => _('degree sign'),
    'º' => _('masculine ordinal'),
    'ª' => _('feminine ordinal'),
    '·' => _('mid-dot'),
    '£' => _('pound sign'),
    '¤' => _('currency sign'),
    '¦' => _('broken bar'),
    'Ð' => _('capital eth'),
    'Þ' => _('capital thorn'),
    'ß' => _('sharp s'),
    'ð' => _('small eth'),
    'þ' => _('small thorn'),
];

maybe_utf8_encode_array($key_titles);
$key_titles = json_encode($key_titles);
