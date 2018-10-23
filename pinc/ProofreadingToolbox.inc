<?php
//include_once($relPath.'site_vars.php'); // $wikihiero_dir $utf8_site
//include_once($relPath.'misc.inc'); // attr_safe(), html_safe()
//include_once($relPath.'CharacterSelector.inc');
//include_once($relPath.'faq.inc');

function draw_tool_button($accesskey, $title, $label, $onclick, $class = '')
{
    $title_safe = attr_safe($title);
    $class_string = $class ? "class='$class'" : '';
    echo "<button type='button' onclick='$onclick' accesskey='$accesskey' $class_string title='$title_safe'>$label</button>";
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
//        $this->character_selector = new CharacterSelector();
    }

    public function render()
    {
//        global $code_url;

        echo "<div id='tool_box'>";
        $this->draw_character_selector();
        $this->draw_tool_buttons();
        echo '</div>';
    }

    function draw_character_selector()
    {
        // this is further constructed by javascript using data from the server
        echo "\n<div id='char-selector' class='float-left'></div>\n";
        echo "<div class='char_box float-left'><input type='text' class='big_text' id='large_char'></div>\n";
    }

    function draw_tool_buttons()
    {
        echo "<div class='float-left'>";
        draw_tool_button('i', _('Italics'), '<i>i</i>', 'textControl.surroundSelection("<i>","</i>");', 'format-tool');
        draw_tool_button('b', _('Bold'), '<b>B</b>', 'textControl.surroundSelection("<b>","</b>");', 'format-tool');
        draw_tool_button('s', _('Small Caps markup'), '<span style="font-variant: small-caps;">Sc</span>', 'textControl.surroundSelection("<sc>","</sc>");', 'format-tool');
        draw_tool_button('g', _('Gesperrt (spaced out)'), 'g', 'textControl.surroundSelection("<g>","</g>");', 'format-tool');
        draw_tool_button('f', _('Antiqua'), 'f', 'textControl.surroundSelection("<f>","</f>");', 'format-tool');
        draw_tool_button('m', _('Remove markup from selection'),"<span style='text-decoration: line-through;'>&lt;x&gt;</span>", 'textControl.transformText("remove_markup");');
        draw_tool_button('u', _('Uppercase selection'), 'ABC', 'textControl.transformText("upper-case");');
        echo "</div>";
    }
}

/* 
        $this->define_tool_button(
            'title_case',
            't',
            _('Title case selection'),
            'Abc',
            "transformText('title-case')"
        );
        $this->define_tool_button(
            'lower_case',
            'l',
            _('Lowercase selection'),
            'abc',
            "transformText('lower-case')"
*/
// vim: sw=4 ts=4 expandtab
