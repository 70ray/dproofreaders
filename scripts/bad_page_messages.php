<?php
$relPath="../pinc/";
include_once($relPath.'misc.inc'); // javascript_safe()

$messages = [
    'markedAsBad' => _("The project has now been marked as bad. Click 'OK' to Return to the Activity Hub"),
    'reportSubmitted' => _("The report has been submitted. Click 'OK' to Return to the Project Page"),
    'selectReason' => _("Please select a reason."),
];

array_walk($messages, function(&$item) { $item = javascript_safe($item);} );
echo "var messages = ",  json_encode($messages), ";\n";
