<?php
//include_once($relPath.'site_vars.php'); // $wikihiero_dir $utf8_site
//include_once($relPath.'misc.inc'); // attr_safe(), html_safe()
include_once($relPath.'CharacterSelector.inc');
//include_once($relPath.'faq.inc');

function draw_tool_button($accesskey, $title, $label, $onclick, $class = '')
{
    $title_safe = attr_safe($title);
    echo "<button type='button' onclick='$onclick' accesskey='$accesskey' class='$class' title='$title_safe'>$label</button>";
}
//    public function __construct($id, $accesskey, $title, $label, $onclick)
//    {
//        $this->id = $id
//    

class ProofreadingToolbox
{
    public function __construct()
    {
//        $this->initialize_popup_links();
//        $this->draw_tool_buttons();
//        $this->initialize_tool_links();
        $this->character_selector = new CharacterSelector();
    }

    public function render()
    {
//        global $code_url;

        echo "<div id='tool_box'>";
        $this->character_selector->echo_block();
        $this->draw_tool_buttons();
        echo '</div>';
    }

    function draw_tool_buttons()
    {
        echo "<div class='float-left'>";
        draw_tool_button('i', _('Italics'), '<i>i</i>', 'textControl.surroundSelection("<i>","</i>");');
        draw_tool_button('b', _('Bold'), '<b>B</b>', 'textControl.surroundSelection("<b>","</b>");');
        draw_tool_button('s', _('Small Caps markup'), '<span style="font-variant: small-caps;">Sc</span>', 'textControl.surroundSelection("<sc>","</sc>");');
        draw_tool_button('g', _('Gesperrt (spaced out)'), 'g', 'textControl.surroundSelection("<g>","</g>");');
        draw_tool_button('f', _('Antiqua'), 'f', 'textControl.surroundSelection("<f>","</f>");');
        echo "</div>";
    }
}

// vim: sw=4 ts=4 expandtab
