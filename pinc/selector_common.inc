<?php

function draw_selectors()
{
    echo "<div class='container'>";
    echo "<label for='codes'>", _("Code"), "</label><select id='codes'></select>";
    echo "<label for='upper-row'>", _("Upper row"), "</label><input type='text' class='mono-spaced' size='30' id='upper-row' readonly>\n";
    echo "<label for='lower-row'>", _("Lower row"), "</label><input type='text' class='mono-spaced' size='30' id='lower-row' readonly>\n";
    echo "</div>";
}

function draw_selector_set()
{
    echo "<label for='selector-set'>", _("Selectors"), "&nbsp;</label><input type='text' size='60' id='selector-set'>\n";
    echo "<button id='save-set'>", _("Save"), "</button><br>";
    echo "<p>", _("This defines the selectors in order. Codes should be separated by a space."), "</p>";
}
// vim: sw=4 ts=4 expandtab
