<?php
include_once($relPath.'ControlButton.inc');

function echo_controls()
{
//    global $code_url, $userP;

    $proof_buttons = [
        new ControlButton('proofControl.revertToOriginal();', _("Revert to Original Document"), 'bt8.png'),
        new ControlButton('proofControl.revertToLastSave();', _("Revert to Last Save"), 'bt7.png', 'revert_button'),
        new ControlButton('proofControl.saveAsInProgress();', _("Save as 'In Progress'"), 'bt3.png'),
        new ControlButton('proofControl.saveAndDoNext();', _("Save as 'Done' & Proofread Next Page"), 'bt2.png'),
        new ControlButton('proofControl.saveAsDone();', _("Save as 'Done'"), 'bt13.png'),
        new ControlButton('proofControl.stopProof();', _("Stop Proofreading"), 'bt1.png'),
        new ControlButton('proofControl.returnPage();', _("Return Page to Round"), 'bt15.png'),
//        new ControlButton('proofControl.wordCheck();', _("WordCheck"), 'bt16.png'),
//        new ControlButton('showNW();', _("Show All Text"), 'bt9.png'),
        new ControlButton('proofControl.reportBadPage();', _("Report Bad Page"), 'bt14.png'),
//        new ControlButton('proofControl.preview();', _("Preview"), 'bt20.png'),
    ];

    echo "<div id='control_div'>";

    // split control buttons
    (new ControlButton('proofControl.setSplit(1);', _('Switch to Vertical'), 'bt4.png', 'h_split'))->draw(true);
    (new ControlButton('proofControl.setSplit(0);', _('Switch to Horizontal'), 'bt5.png', 'v_split'))->draw(true);

    echo "<div id='proof_control' class='inline'>space filler space filler space filler\n";

    // settings dropdown - do not close on any click or ms edge and ie color controls won't work
    echo "<div class='dropdown'><button type='button' class='dropdown_button' onclick='proofControl.showMenu(\"proof_settings\", 1);'>", html_safe(_('Settings')), "&nbsp;^</button><div class='proof-menu-content nodisp setting-grid-wrapper' id='proof_settings'>\n";
    echo "<div class='right-align'><label for='image_back_color'>", html_safe(_('Image Background')), "</label></div><div><input type='color' id='image_back_color' onchange='proofControl.setImageBackColor(this)'></div>\n";
    echo "<div class='right-align'><label for='text_color'>", html_safe(_('Text Color')), "</label></div><div><input type='color' id='text_color' onchange='proofControl.setTextColor(this);'></div>\n";
    echo "<div class='right-align'><label for='back_color'>", html_safe(_('Text Background')), "</label></div><div><input type='color' id='back_color' onchange='proofControl.setBackColor(this)'></div>\n";
    echo "<div class='box_2col center-align'><button type='button' onclick='proofControl.restoreColors()'>", html_safe(_('Restore Default Colors')), "</button></div>\n";
    echo "<div class='right-align'><label for='show_icons'>", html_safe(_('Show Icons')), "</label></div><div><input type='checkbox' id='show_icons' onchange='proofControl.iconControl(this);' checked ></div>\n";
    echo "<div class='right-align'><label for='wrap_text'>", html_safe(_('Wrap Text')), "</label></div><div><input type='checkbox' id='wrap_text' onchange='proofControl.wrapControl(this);'></div>\n";

    echo "<div class='right-align'><button type='button' onclick='proofControl.addFont()'>", html_safe(_('Add Font')), "</button></div><div><input type='text' size='20' id='new-font'></div>\n";
    echo "<div class='right-align'><button type='button' onclick='proofControl.removeFont()'>", html_safe(_('Remove Font')), "</button></div><div><select id='font-remove'></select></div>\n";

    echo "<div class='box_2col center-align'><button type='button' onclick='proofControl.saveProfile()'>", html_safe(_('Save Profile')), "</button></div>\n";
    echo "</div></div>\n"; // end of Settings dropdown

    echo "<div class='dropdown'><button type='button' class='dropdown_button' onclick='proofControl.showMenu(\"proof_menu\", 2);'>", html_safe(_('Menu')), "&nbsp;^</button><div class='proof-menu-content nodisp' id='proof_menu'>\n";
    foreach($proof_buttons as $button)
    {
        $button->draw(false);
        echo "<br>\n";
    }
    echo "</div></div>\n"; // end of menu dropdown

    echo "<select id='font-select' title='", attr_safe(_("Change Font Face")), "' onChange='proofControl.changeFontFamily(this)'></select>\n";
    echo "<select id='font-size-select' title='", attr_safe(_("Change Font Size")), "' onChange='proofControl.changeFontSize(this)'></select>\n";

    echo "<div id='icon_bar' class='inline'>";
    foreach($proof_buttons as $button)
    {
        $button->draw(true);
    }

    echo "</div>"; // end of icon_bar
    echo "</div>"; // end of proof_control div
    echo "</div>"; // end of control div
}
