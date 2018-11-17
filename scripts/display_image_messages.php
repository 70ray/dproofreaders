<?php
$relPath="../pinc/";
include_once($relPath.'misc.inc');

$messages = [
    'noImages' => _("There are no images"),
    'absentImage' => _("The image '%s' is missing"),
    'returnToProject' => _("Return to Project Page for %s"),
];

//array_walk($messages, function(&$item) { $item = javascript_safe($item);} );
echo "var messages = ",  json_encode($messages, JSON_UNESCAPED_UNICODE), ";\n";
