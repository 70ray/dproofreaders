<?php

// enables a button to be drawn either with an icon or with text
class ControlButton
{
    function __construct($handler, $label, $image, $class_name = '', $title = '')
    {
        $this->handler = $handler;
        if($title == '')
        {
            $title = $label;
        }
        $this->label = $label;
        $this->attr_title = attr_safe($title);
        $this->image = $image;
        $this->class_names = $class_name;
    }

    function draw($use_image)
    {
        if($use_image)
        {
            // middle align prevents extra space in button below image
            $caption = "<img src='gfx/$this->image' class='middle-align' alt='$this->attr_title'>";
            $this->class_names .= ' image-button'; // this removes padding
        }
        else
        {
            $caption = html_safe($this->label);
        }
        $class_string = '';
        if($this->class_names != '')
        {
            $class_string = "class='$this->class_names' ";
        }
        echo "<button type='button' onclick='$this->handler' {$class_string}title='$this->attr_title'>$caption</button>";
    }
}

function echo_controls()
{
//    global $code_url, $userP;

    $proof_buttons = [
        new ControlButton('proofControl.revertToOriginal();', _("Save & Revert to Original Document"), 'bt8.png', 'proof-only'),
        new ControlButton('proofControl.revertToLastSave();', _("Revert to Last Save"), 'bt7.png', 'revert_button proof-only'),
        new ControlButton('proofControl.saveAsInProgress();', _("Save as 'In Progress'"), 'bt3.png', 'proof-only'),
        new ControlButton('proofControl.saveAndDoNext();', _("Save as 'Done' & Proofread Next Page"), 'bt2.png', 'proof-only'),
        new ControlButton('proofControl.saveAsDone();', _("Save as 'Done'"), 'bt13.png', 'proof-only'),
        new ControlButton('proofControl.stopProof();', _("Stop Proofreading"), 'bt1.png', 'proof-only'),
        new ControlButton('proofControl.returnPage();', _("Return Page to Round"), 'bt15.png', 'proof-only'),
        new ControlButton('proofControl.wordCheck();', _("WordCheck"), 'bt16.png', 'proof-only'),
//        new ControlButton('showNW();', _("Show All Text"), 'bt9.png'),
        new ControlButton('proofControl.reportBadPage();', _("Report Bad Page"), 'bt14.png', 'proof-only'),
//        new ControlButton('proofControl.preview();', _("Preview"), 'bt20.png'),
         new ControlButton('proofControl.projectComments();', _("Project Comments"), 'bt12.png', '', _("View Project Comments in New Window")),
         new ControlButton('proofControl.viewImage();', _("Image"), 'bt10.png', '', _("View Image in New Window")),
    ];

    echo "<div id='control_div'>";

    // split control buttons
    (new ControlButton('proofControl.setSplit(1);', _('Switch to Vertical'), 'bt4.png', 'h_split'))->draw(true);
    (new ControlButton('proofControl.setSplit(0);', _('Switch to Horizontal'), 'bt5.png', 'v_split'))->draw(true);

    // Image size control
    echo "<button type='button' onclick='proofControl.zoomImage(0.909)' title='", attr_safe(_('Zoom out')), "'>-</button>";
    echo "<button type='button' onclick='proofControl.zoomImage(1.1)' title='", attr_safe(_('Zoom in')), "'>+</button>";
    echo "<button type='button' onclick='proofControl.sizeImage(\"fit-width\")' title='", attr_safe(_('Fit Width')), "'>&harr;</button>";
    echo "<button type='button' onclick='proofControl.sizeImage(\"fit-height\")' title='", attr_safe(_('Fit Height')), "'>&varr;</button>";
    echo "<button type='button' onclick='proofControl.sizeImage(\"intrinsic\")' title='", attr_safe(_('Intrinsic Size')), "'>I</button>";

    // profile menu - close on click
    echo "<div class='dropdown proof-only'><button type='button' class='dropdown_button' onclick='proofControl.showProfileMenu(this);'>", html_safe(_('Profile')), "&nbsp;^</button><div class='proof-menu-content nodisp setting-grid-wrapper'>\n";
    echo "<div class='box_2col center-align' id='profile-name'>Profile name</div>\n";
    echo "<div class='right-align'><button type='button' onclick='proofControl.selectProfile();'>", html_safe(_('Select Profile')), "</button></div><div><select id='list-select-profiles'></select></div>\n";
    echo "<div class='right-align'><button type='button' onclick='proofControl.saveProfile();'>", html_safe(_('Save')), "</button></div><div></div>\n";
    echo "<div class='right-align'><button type='button' onclick='proofControl.saveNewProfile();'>", html_safe(_('Save as new')), "</button></div><div><input type='text' size='10' id='new-profile'></div>\n";
    echo "<div class='right-align'><button type='button' onclick='proofControl.saveAsProfile();'>", html_safe(_('Save as')), "</button></div><div><select id='list-save-profiles'></select></div>\n";
    echo "<div class='right-align'><button type='button' onclick='proofControl.deleteProfile();'>", html_safe(_('Delete')), "</button></div><div><select id='list-delete-profiles'></select></div>\n";
    echo "</div></div>\n"; // end of profile dropdown

    // settings dropdown - do not close on any click or ms edge and ie color controls won't work
    echo "<div class='dropdown proof-only'><button type='button' class='dropdown_button' onclick='proofControl.showMenu(this, 1);'>", html_safe(_('Settings')), "&nbsp;^</button><div class='proof-menu-content nodisp setting-grid-wrapper'>\n";
    echo "<div class='right-align'><label for='image_back_color'>", html_safe(_('Image Background')), "</label></div><div><input type='color' id='image_back_color' onchange='proofControl.setImageBackColor(this)'></div>\n";
    echo "<div class='right-align'><label for='text_color'>", html_safe(_('Text Color')), "</label></div><div><input type='color' id='text_color' onchange='proofControl.setTextColor(this);'></div>\n";
    echo "<div class='right-align'><label for='back_color'>", html_safe(_('Text Background')), "</label></div><div><input type='color' id='back_color' onchange='proofControl.setBackColor(this)'></div>\n";
//    echo "<div class='box_2col center-align'><button type='button' onclick='proofControl.restoreColors()'>", html_safe(_('Restore Default Colors')), "</button></div>\n";
    echo "<div class='right-align'><label for='show_icons'>", html_safe(_('Show Icons')), "</label></div><div><input type='checkbox' id='show_icons' onchange='proofControl.iconControl(this);' checked ></div>\n";
    echo "<div class='right-align'><label for='wrap_text'>", html_safe(_('Wrap Text')), "</label></div><div><input type='checkbox' id='wrap_text' onchange='proofControl.wrapControl(this);'></div>\n";

    echo "<div class='right-align'><button type='button' onclick='proofControl.addFont()'>", html_safe(_('Add Font')), "</button></div><div><input type='text' size='20' id='new-font'></div>\n";
    echo "<div class='right-align'><button type='button' onclick='proofControl.removeFont()'>", html_safe(_('Remove Font')), "</button></div><div><select id='font-remove'></select></div>\n";

    echo "</div></div>\n"; // end of Settings dropdown

    // proofreading menu
    echo "<div class='dropdown'><button type='button' class='dropdown_button' onclick='proofControl.showMenu(this, 2);'>", html_safe(_('Menu')), "&nbsp;^</button><div class='proof-menu-content nodisp'>\n";
    foreach($proof_buttons as $button)
    {
        $button->draw(false);
        echo "<br>\n";
    }
    echo "</div></div>\n"; // end of menu dropdown

    // font controls
    echo "<select id='font-select' class='proof-only' title='", attr_safe(_("Change Font Face")), "' onChange='proofControl.changeFontFamily(this)'></select>\n";
    echo "<select id='font-size-select' class='proof-only' title='", attr_safe(_("Change Font Size")), "' onChange='proofControl.changeFontSize(this)'></select>\n";

    // icons
    echo "<div id='icon_bar' class='inline'>";
    foreach($proof_buttons as $button)
    {
        $button->draw(true);
    }

    echo "</div>\n"; // end of icon_bar
    echo "<button type='button' class='WC-only' onclick='wordCheck.quit();' title='", attr_safe(_("Abandon corrections and return to proofreading this page")), "'>", html_safe(_('Quit WordCheck')), "</button>\n";
    echo "<button type='button' class='WC-only' onclick='wordCheck.applyCorrections();' title='", attr_safe(_("Keep corrections and return to proofreading this page")), "'>", html_safe(_('Submit Corrections')), "</button>\n";

    echo "<div class='dropdown WC-only'><button type='button' class='dropdown_button' onclick='proofControl.showMenu(this, 1);'>", html_safe(_('Dictionaries')), "&nbsp;^</button><div class='proof-menu-content nodisp'>\n";
    echo "<span id='lang-data' class='WC-only'></span>
<select id='lang_select' class='WC-only' multiple></select>
<button type='button' class='WC-only' onclick='wordCheck.rerunAuxLang();' title='", attr_safe(_("Check page against an additional language")), "'>", html_safe(_('Check')), "</button>";
    echo "</div></div>\n"; // end of Dictionaries dropdown

    echo "&nbsp;<span id='page-info' class='small bold'></span>";
    echo "</div>"; // end of control div
}
