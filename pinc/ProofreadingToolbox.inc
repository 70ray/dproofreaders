<?php
//include_once($relPath.'site_vars.php'); // $wikihiero_dir $utf8_site
//include_once($relPath.'misc.inc'); // attr_safe(), html_safe()
//include_once($relPath.'CharacterSelector.inc');
//include_once($relPath.'faq.inc');

function draw_tool_button($accesskey, $title, $label, $onclick, $class = '')
{
    $title_safe = attr_safe($title);
    $access_string = $accesskey ? "accesskey='$accesskey'" : '';
    $class_string = $class ? "class='$class'" : '';
    echo "<button type='button' onclick='$onclick' $access_string $class_string title='$title_safe'>$label</button>\n";
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
        echo "<div class='proof-only'>";
        draw_tool_button('i', _('Italics'), '<i>i</i>', 'textControl.surroundSelection("<i>","</i>");', 'format-tool');
        draw_tool_button('b', _('Bold'), '<b>B</b>', 'textControl.surroundSelection("<b>","</b>");', 'format-tool');
        draw_tool_button('s', _('Small Caps markup'), '<span style="font-variant: small-caps;">Sc</span>', 'textControl.surroundSelection("<sc>","</sc>");', 'format-tool');
        draw_tool_button('g', _('Gesperrt (spaced out)'), 'g', 'textControl.surroundSelection("<g>","</g>");', 'format-tool');
        draw_tool_button('f', _('Antiqua'), 'f', 'textControl.surroundSelection("<f>","</f>");', 'format-tool');
        draw_tool_button('m', _('Remove markup from selection'),"<span style='text-decoration: line-through;'>&lt;x&gt;</span>", 'textControl.transformText("remove_markup");');
        draw_tool_button('u', _('Uppercase selection'), 'ABC', 'textControl.transformText("upper-case");');
        draw_tool_button('t', _('Title case selection'), 'Abc', 'textControl.transformText("title-case");');
        draw_tool_button('l', _('Lowercase selection'), 'abc', 'textControl.transformText("lower-case");');
        draw_tool_button('', '[Greek:', '[Greek: ]', 'textControl.surroundSelection("[Greek: ","]");');
        draw_tool_button('', '[Sidenote:', '[Sidenote: ]', 'textControl.surroundSelection("[Sidenote: ","]");', 'format-tool');
        draw_tool_button('', '[Illustration:', '[Illustration: ]', 'textControl.surroundSelection("[Illustration: ","]");', 'format-tool');
        draw_tool_button('', _('Note'), '[** ]', 'textControl.surroundSelection("[** ","]");');
        draw_tool_button('', '[ ]', '[ ]', 'textControl.surroundSelection("[","]");');
        draw_tool_button('', '{ }', '{ }', 'textControl.surroundSelection("{","}");');
        draw_tool_button('', '[Footnote #:', '[Footnote #: ]', 'textControl.surroundSelection("[Footnote #: ","]");', 'format-tool');
        draw_tool_button('', _('Start poetry/table'), '/* */', 'textControl.surroundSelection("/*\\n","\\n*/");', 'format-tool');
        draw_tool_button('', _('Start block quote'), '/# #/', 'textControl.surroundSelection("/#\\n","\\n#/");', 'format-tool');
        draw_tool_button('', _('Thought Break'), '&lt;tb&gt;', 'textControl.surroundSelection("\\n<tb>\\n","");', 'format-tool');
        draw_tool_button('', '[Blank Page]', '[Blank Page]', 'textControl.replaceAllText("[Blank Page]");');
        echo "</div>";
    }
}

// vim: sw=4 ts=4 expandtab
