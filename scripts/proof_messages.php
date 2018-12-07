<?php
$relPath="../pinc/";
include_once($relPath.'base.inc');
include_once($relPath.'utils.inc');

$messages = [
    'confirmReturn' => _("This will discard all changes you have made on this page. Are you sure you want to return this page to the current round?"),
    'confirmStop' => _('Are you sure you want to stop proofreading?'),
    'confirmRevertOrig' => _("Are you sure you want to revert to the original text for this round?"),
    'confirmRevertToLastSave' => _("Are you sure you want to revert to your last save?"),
    'pageNumber' => _("Page: %s"),
    'cannotDeleteFont' => _("You cannot delete the current font"),
    'confirmRemove' => _("Are you sure you want to remove %s?"),
    'confirmExit' => _("Changes have been made. OK to quit without saving?"),
    'unflag' => _("Unflag All &amp; Suggest Word"),
    'disableAWLabel' => _("Word has been edited; unable to Suggest"),
    'dictionariesUsed' => _("Dictionaries used: <b>%s</b>."),
    'pageChangedError' => _("Cannot save page from WordCheck. Words have been modified."),
    'saveAndNext' => _("Save page as done and proofread the next available page"),
];

maybe_encode_array($messages);
echo "var messages = ", json_encode($messages), ";\n";
