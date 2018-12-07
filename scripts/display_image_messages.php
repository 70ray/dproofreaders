<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'utils.inc');

$messages = [
    'noImages' => _("There are no images"),
    'absentImage' => _("The image '%s' is missing"),
    'returnToProject' => _("Return to Project Page for %s"),
];

maybe_encode_array($messages);
echo "var messages = ",  json_encode($messages), ";\n";
