<?php
$relPath='../../pinc/';
include_once($relPath.'base.inc');
include_once($relPath.'theme.inc');
include_once($relPath.'prefs_options.inc');

output_header(_('Proofreading Tutorial and Interactive Quiz -- Step 4'));

echo "<h2>" . _("Part 4") . "</h2>";

echo "<h3>" . _("Illustrations") . "</h3>";

echo "<p>" . _("Text for an illustration should be surrounded by <tt>[Illustration: the-text-caption]</tt>.  If there is no text caption, just place <tt>[Illustration]</tt> there. If it is in the middle of a paragraph or on the side of a paragraph, move the <tt>[Illustration: the-text] </tt>to either above or below the paragraph, based on where you can put it without it being in the middle of a paragraph.  If the text caption is printed on multiple lines, put it that way in your proofread text.") . "</p>";

echo "<h3>" . _("Footnotes/Endnotes") . "</h3>";

echo "<p>" . _("<b>Footnotes are placed out-of-line</b>; that is, the text of the footnote is left at the bottom of the page and a tag placed where it is referenced in the text.") . "</p>";

echo "<p>" . _("During proofreading, this means:") . "</p>";

echo "<p>" . _("1. the number, letter, *, or other character that marks a footnote location should be surrounded with brackets (<tt>[</tt> and <tt>]</tt>).  Sometimes footnotes are marked with a series of special characters (*, &dagger;, &Dagger;, &sect;, etc.) that are not available in standard ASCII.  In this case, we replace these with Capital letters in order (A, B, C, etc.) when proofreading. Don't leave any space before the <tt>[</tt>--keep it right next to the word being footnoted. (This prevents it from ending up on a separate line when the paragraphs are eventually re-flowed.)") . "</p>";

echo "<p>" . _("2. at the bottom of the page, where the text for that footnote is, surround it with <tt>[Footnote _: the-text]</tt>, inserting the footnote number, letter, or Roman numeral where the underline is. Be sure to use the same tag in the footnote as you used in the text where the footnote was referenced.") . "</p>";

echo "<a href='../generic/main.php?type=step4'>" . _("continue") . "</a>";

?>
