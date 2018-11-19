<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');

$messages = [
    'noImages' => _("There are no images"),
    'absentImage' => _("The image '%s' is missing"),
    'returnToProject' => _("Return to Project Page for %s"),
];

if(!$utf8_site)
{
    array_walk($messages, function(&$item) { $item = utf8_encode($item);} );
}
echo "var messages = ",  json_encode($messages), ";\n";
