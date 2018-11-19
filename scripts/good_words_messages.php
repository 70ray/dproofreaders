<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');

$messages = [
    'lastMod' => _("Suggestions since the project's Good Words List was last modified are included."),
    'allSuggestions' => _("<b>All proofreader suggestions</b> are included in the results."),
    'pastDays' => _("Only proofreader suggestions made <b>after %s</b> are included in the results."),
    'submitLabel' => _("Add selected words to Good Words List"),
    'context' => _("Context"),
    'sugg' => _("Sugg"),
    'showContext' => _("Show Context"),
    'word' => _('Word'),
    'state' => pgettext("project state", "State"),
    'selectAll' => _("Select all"),
    'unSelectAll' => _("Unselect all")
];

if(!$utf8_site)
{
    array_walk($messages, function(&$item) { $item = utf8_encode($item);} );
}
echo "var messages = ",  json_encode($messages), ";\n";
