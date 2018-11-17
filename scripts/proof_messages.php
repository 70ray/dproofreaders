<?php
//$relPath="../pinc/";
//include_once($relPath.'misc.inc');

$messages = [
    'confirmReturn' => _("This will discard all changes you have made on this page. Are you sure you want to return this page to the current round?"),
    'confirmStop' => _('Are you sure you want to stop proofreading?'),
    'confirmRevertOrig' => _("Are you sure you want to revert to the original text for this round?"),
    'confirmRevertToLastSave' => _("Are you sure you want to revert to your last save?"),
    'pageNumber' => _("Page: %s"),
    'cannotDeleteFont' => _("You cannot delete the current font"),
    'confirmRemove' => _("Are you sure you want to remove %s?"),
];

/*function make_safe(&$item)
{
    $item = javascript_safe($item);
}*/
//array_walk($messages, 'make_safe');
echo "var messages = ",  json_encode($messages, JSON_UNESCAPED_UNICODE), ";\n";
