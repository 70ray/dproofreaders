<?php
$relPath="../pinc/";
include_once($relPath.'misc.inc');

$messages = [
    'lastMod' => _("Suggestions since the project's Good Words List was last modified are included."),
    'allSuggestions' => _("<b>All proofreader suggestions</b> are included in the results."),
    'pastDays' => _("Only proofreader suggestions made <b>after %s</b> are included in the results.")
];

array_walk($messages, function(&$item) { $item = javascript_safe($item);} );
echo "var messages = ",  json_encode($messages), ";\n";
