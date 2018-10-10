<?php
$relPath="../pinc/";
include_once($relPath.'misc.inc');

$messages = [
    'noImages' => javascript_safe(_("There are no images")),
    'absentImage' => javascript_safe(_("The image '%s' is missing")),
    'returnToProject' => javascript_safe(_("Return to Project Page for %s")),
];

echo "var messages = ",  json_encode($messages);
