<?php
//include_once($relPath.'site_vars.php'); // $wikihiero_dir $utf8_site
//include_once($relPath.'misc.inc'); // attr_safe(), html_safe()
include_once($relPath.'CharacterSelector.inc');
//include_once($relPath.'faq.inc');

class ProofreadingToolbox
{
    public function __construct()
    {
//        $this->initialize_popup_links();
//        $this->initialize_tool_buttons();
//        $this->initialize_tool_links();
        $this->character_selector = new CharacterSelector();
    }

    public function render()
    {
//        global $code_url;

        echo "<div id='tool_box'>";
//        echo "toolbox";
//        echo '<a href="#" accesskey="=" onfocus="top.focusText()"></a>';
//        echo '<form name="markform" id="markform" onsubmit="return(false);" action="ctrl_frame.php">';
//        echo '<table style="width: 100%;" class="center-align">';
//        echo "<tr>";
//        echo '<td class="top-align right-align">';
        $this->character_selector->echo_block();
/*        echo '</td>';
        echo '<td class="top-align center-align">';
        echo <<<EOS
        <input
            accesskey="["
            type="text"
            value=""
            name="markBox"
            class="dropnormal"
            size="9"
            onclick="this.select();"
        >
        <input
            accesskey="]"
            type="text"
            value=""
            name="markBoxEnd"
            class="dropnormal"
            size="9"
            onclick="this.select()"
        >
EOS;

        $this->echo_tool_buttons( $round->pi_tools['tool_buttons'] );

        echo '</td>';
        echo '<td class="top-align right-align">';
        $help = _("Help");
        echo "<b><span style='color: red;'>$help---&gt;</span></b>";
        echo "<a href='../../faq/prooffacehelp.php' accesskey='1' target='helpNewWin'>";
        echo <<<EOS
        <img
            src="gfx/tags/help.png"
            width="18"
            height="18"
            border="0"
            class="top-align"
            alt="$help"
            title="$help"
        >
        </a>
EOS;
        echo '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td class="top-align center-align" colspan="3">';
        echo "<span class='small'>\n";

        echo "<i>"._("Markup shortcuts").":</i>\n";
        $this->echo_tool_links( $round->pi_tools['tool_links'] );
        echo "\n<br>\n";

        echo "<i>". _('Pop-up tools'). ":</i>\n";
        $this->echo_popup_links( $round->pi_tools['popup_links'] );
        echo "\n<br>\n";

        echo "<i>"._("Reference Information").":</i> ";
        $url = get_faq_url($round->document);
        echo "[<a style=\"color:#0000FF; text-decoration: underline;\" href='$url' target='_blank'>";
        echo _('Guidelines');
        echo "</a>]\n";

        $url = "$code_url/faq/InterfaceDiagram.png";
        echo "[<a style=\"color:#0000FF; text-decoration: underline;\" href='$url' target='_blank'>";
        echo _("Proofreading Diagram");
        echo "</a>]\n";

        echo "</span>\n";
        echo '</td>';
        echo '</tr>';
        echo '</table>';
        echo '</form>';*/
        echo '</div>';
    }
}

// vim: sw=4 ts=4 expandtab
