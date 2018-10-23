<?php
include_once($relPath.'ControlButton.inc');

function echo_controls()
{
//    global $code_url, $userP;
    $split_control_buttons = [
        new ControlButton('proofControl.setSplit(1);', _('Switch to Vertical'), 'bt4.png', 'h_split'),
        new ControlButton('proofControl.setSplit(0);', _('Switch to Horizontal'), 'bt5.png', 'v_split')
    ];

    $proof_buttons = [
        new ControlButton('proofControl.revertToOriginal();', _("Revert to Original Document"), 'bt8.png'),
        new ControlButton('proofControl.revertToLastSave();', _("Revert to Last Save"), 'bt7.png', 'revert_button'),
        new ControlButton('proofControl.saveAsInProgress();', _("Save as 'In Progress'"), 'bt3.png'),
        new ControlButton('proofControl.saveAndDoNext();', _("Save as 'Done' & Proofread Next Page"), 'bt2.png'),
        new ControlButton('proofControl.saveAsDone();', _("Save as 'Done'"), 'bt13.png'),
        new ControlButton('proofControl.stopProof();', _("Stop Proofreading"), 'bt1.png'),
        new ControlButton('proofControl.returnPage();', _("Return Page to Round"), 'bt15.png'),
/*        new ControlButton('proofControl.wordCheck();', _("WordCheck"), 'bt16.png'),
        new ControlButton('showNW();', _("Show All Text"), 'bt9.png'),
        new SubmitButton('report_bad_page', '', _("Report Bad Page"), 'bt14.png'),
        new ControlButton('proofControl.preview();', _("Preview"), 'bt20.png'),*/
    ];

/*    echo "<div id='control_div'>";

    $ppage->echo_info();

    draw_class_button('h_split', 'proofControl.setVSplit();', _('Switch to Vertical'), 'bt4.png');
    draw_class_button('v_split', 'proofControl.setHSplit();', _('Switch to Horizontal'), 'bt5.png');

    echo "<span class='ilb'>", html_safe(_("Image")), "&nbsp;";
    echo "<button type='button' onclick='proofControl.zoomImage(0.909)' title='", attr_safe(_('Zoom out')), "'>-</button>";
    echo "<button type='button' onclick='proofControl.zoomImage(1.1)' title='", attr_safe(_('Zoom in')), "'>+</button>";
    echo "<button type='button' onclick='proofControl.fitWidth()' title='", attr_safe(_('Fit Width')), "'>&harr;</button>";
    echo "<button type='button' onclick='proofControl.fitHeight()' title='", attr_safe(_('Fit Height')), "'>&varr;</button>";
    echo "<button type='button' onclick='proofControl.intrinsic()' title='", attr_safe(_('Intrinsic Size')), "'>I</button>";
    echo "</span>";

    echo "<div id='proof_control' class='inline'>\n";

    // settings dropdown - do not close on any click or ms edge and ie color controls won't work
    echo "<div class='dropdown'><button type='button' class='dropdown_button' onclick='proofControl.showMenu(\"proof_settings\", 1);'>", html_safe(_('Settings')), "&nbsp;^</button><div class='proof-menu-content nodisp setting-grid-wrapper' id='proof_settings'>\n";
    echo "<div class='right-align'><label for='image_back_color'>", html_safe(_('Image Background')), "</label></div><div><input type='color' id='image_back_color' onchange='proofControl.setImageBackColor(this)'></div>\n";
    echo "<div class='right-align'><label for='text_color'>", html_safe(_('Text Color')), "</label></div><div><input type='color' id='text_color' onchange='proofControl.setTextColor(this);'></div>\n";
    echo "<div class='right-align'><label for='back_color'>", html_safe(_('Text Background')), "</label></div><div><input type='color' id='back_color' onchange='proofControl.setBackColor(this)'></div>\n";
    echo "<div class='box_2col center-align'><button type='button' onclick='proofControl.restoreColors()'>", html_safe(_('Restore Default Colors')), "</button></div>\n";
    echo "<div class='right-align'><label for='show_icons'>", html_safe(_('Show Icons')), "</label></div><div><input type='checkbox' id='show_icons' onchange='proofControl.iconControl(this);' checked ></div>\n";
    echo "<div class='right-align'><label for='wrap_text'>", html_safe(_('Wrap Text')), "</label></div><div><input type='checkbox' id='wrap_text' onchange='proofControl.wrapControl(this);'></div>\n";
    echo "</div></div>\n"; // end of Settings dropdown

    echo "<div class='dropdown'><button type='button' class='dropdown_button' onclick='proofControl.showMenu(\"proof_menu\", 2);'>", html_safe(_('Menu')), "&nbsp;^</button><div class='proof-menu-content nodisp' id='proof_menu'>\n";*/
    echo "<div>";
    foreach($split_control_buttons as $button)
    {
        $button->draw(true);
    }
    foreach($proof_buttons as $button)
    {
        $button->draw(false);
    //    echo "<br>\n";
    }
    echo "</div>";
    /*echo "</div></div>\n"; // end of menu dropdown*/
}