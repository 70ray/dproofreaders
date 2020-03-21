<?php
function draw_size_controls()
{
    echo "<input type='number' id='percent' min='1' max='999' value='100'>%\n";
    echo "<button type='button' id='resize'>", _("Resize"), "</button>\n";
}

function get_images($projectid)
{
    $images = array();
    $res = mysqli_query(DPDatabase::get_connection(), "SELECT image FROM $projectid ORDER BY image ASC") or die(mysqli_error(DPDatabase::get_connection()));
    while($row = mysqli_fetch_assoc($res))
    {
        $images[] = $row["image"];
    }
    mysqli_free_result($res);
    return $images;
}

function draw_page_selector($images, $selected_page)
{
    echo _("Page") . ":&nbsp;<select name='page' id='page-select'>";
    foreach($images as $image)
    {
        $selected = ($image == $selected_page) ? " selected" : "";
        echo "<option value='$image'$selected>$image</option>\n";
    }
    echo "</select>&nbsp;";

    echo "<input type='button' id='prev-button' value='" . attr_safe(_("Previous")) . "'>\n";
    echo "<input type='button' id='next-button' value='" . attr_safe(_("Next")) . "'>\n";
}

function draw_round_selector($expanded_rounds, $round_id)
{
    echo _("Round") . ":&nbsp;<select name='round_id' id='round'>";
    foreach ($expanded_rounds as $round)
    {
        $selected = ($round == $round_id) ? " selected" : "";
        echo "<option value='$round'$selected>$round</option>\n";
    }
    echo "</select>";
}
