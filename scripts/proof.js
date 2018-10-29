/*global document window Image projectsUrl alert codeUrl imageFile messages
 $ apiUrl projectID projState imageID pageState confirm textControl picker
initSplit Element */

var proofControl;
$(function () {
    "use strict";
    var scanImage = document.getElementById("scanimage");
    var fontSelector = document.getElementById("font-select");
    var removeFontSelector = document.getElementById("font-remove");
    var textArea = $("#text_area");
    var imageDiv = $("#imagedisplay");
    var imageUrl;
//    var splitControl;
    var profile = {};

    var settings = {
        profiles: {
            profile_0: {
                split: 1,
                ratio: 0.5,
                imageBackground: "#CDC0B0",
                textColor: "#000000",
                textBackground: "#ffffff",
                showIcons: true,
                fontFamily: 'monospace',
                fontSize: '14px',
                zoom: 100,
                wrap: false
            }
        },
        profileName: "profile_0",
        fonts: {'monospace': 0, 'Arial': 0, 'Courier': 0, 'DPCustomMono2': 0, 'Lucida': 0, 'Lucida Console': 0, 'Consolas': 0}
    };

    var defaultStyle = {
        split: 1,
        imageBackground: "#CDC0B0",
        textColor: "#000000",
        textBackground: "#ffffff",
        showIcons: true,
        fontFamily: 'monospace',
        fontSize: '14px'
    };

    function sprintf(string, p1) {
        return string.replace("%s", p1);
    }

    function setupToolbox(isFormatting) {
        if (!isFormatting) {
            $(".format-tool", '#tool_box').hide();
        }
    }

    function setPageState(data) {
        pageState = data.pageState;
        // there could be a text button and an icon button
        // must end with 'temp' or 'out'
        var disableButton = (pageState.slice(-3) === "out");
        $(".revert_button").prop("disabled", disableButton);
    }

    function loadState(data) {
        setPageState(data);
    }

    function loadText(data) {
//    console.log(data);
        setPageState(data);
        textArea.val(data.text);
        textArea.focus();
        textControl.setCaret(0);
    }

    function loadImageText(data) {
//    console.log(data);
        imageID = data.imageID;
        scanImage.src = imageUrl + data.imageID;
        scanImage.alt = data.imageID;
        loadText(data);
        setupToolbox(data.isFormattingRound);
    }

    function toProjectPage() {
        window.location.replace(codeUrl + "project.php?id=" + projectID + "&expected_state=" + projState);
    }

    function deepCopy(dest, source, keep) {
        var i;
        if (source && typeof source === 'object') {
//            if (!keep) {
  //              dest = Array.isArray(source) ? [] : {};
    //        }
            if (!dest) {
                dest = Array.isArray(source) ? [] : {};
            }
            for (i in source) {
                dest[i] = deepCopy(dest[i], source[i], keep);
            }
        } else {
            dest = source;
        }
        return dest;
    }

    function setColors() {
        imageDiv.css("backgroundColor", profile.imageBackground);
        $('#image_back_color').val(profile.imageBackground);
        textArea.css('color', profile.textColor);
        $('#text_color').val(profile.textColor);
        textArea.css('backgroundColor', profile.textBackground);
        $('#back_color').val(profile.textBackground);
    }

    function setupIcons() {
        $('#icon_bar')[profile.showIcons ? 'show' : 'hide']();
    }

    function splitButtonsSetup() {
        var mode = profile.split;
        $(".v_split", ".control-div")[mode ? 'show' : 'hide']();
        $(".h_split", ".control-div")[mode ? 'hide' : 'show']();
    }

    function setTextFontFamily() {
        textArea.css("fontFamily", profile.fontFamily);
    }

    function setupSelector(selector, optionList, selectedOption, exclude) {
        // empty it first
        while (selector.length) {
            selector.remove(0);
        }
        optionList.forEach(function (item) {
            if (!(exclude && (item === selectedOption))) {
                var opt = document.createElement("option");
                opt.value = item;
                opt.text = item;
                if (item === selectedOption) {
                    opt.selected = true;
                }
                selector.add(opt);
            }
        });
    }

    function drawProfileList() {
        var optionList = [];
        var prof;
        for (prof in settings.profiles) {
            optionList.push(prof);
        }
        optionList.sort();
        var listSelector = document.getElementById("list-select-profiles");
        var saveSelector = document.getElementById("list-save-profiles");
        var deleteSelector = document.getElementById("list-delete-profiles");
        setupSelector(listSelector, optionList, settings.profileName);
        setupSelector(saveSelector, optionList, settings.profileName, true);
        setupSelector(deleteSelector, optionList, settings.profileName, true);
    }

    function setupFontFamilySelectors() {
        var optionList = [];
        var font;
        for (font in settings.fonts) {
            optionList.push(font);
        }
        optionList.sort();
        setupSelector(fontSelector, optionList, profile.fontFamily);
        setupSelector(removeFontSelector, optionList);
    }

    function setTextFontSize() {
        textArea.css("font-size", profile.fontSize);
    }

    function setupFontSize() {
        setTextFontSize();
        var fontSizeSelector = document.getElementById("font-size-select");
        var fontSize = 10;
        function addOption() {
            var opt = document.createElement("option");
            var sizeString = fontSize + "px";
            opt.value = sizeString;
            opt.text = sizeString;
            if (sizeString === profile.fontSize) {
                opt.selected = true;
            }
            fontSizeSelector.add(opt);
        }
        while (fontSize < 22) {
            addOption();
            fontSize += 1;
        }
        while (fontSize < 42) {
            addOption();
            fontSize += 2;
        }
    }

    function setWrap() {
        var wrap = profile.wrap ? 'soft' : 'off';
        textArea.prop("wrap", wrap);
    }

    function setZoom() {
        var zoom = profile.zoom;
//        console.log(zoom);
        switch (zoom) {
        case 'fit-width':
            scanImage.style.width = '100%';
            scanImage.style.height = 'auto';
            break;
        case 'fit-height':
            scanImage.style.width = 'auto';
            scanImage.style.height = '100%';
            break;
        case 'intrinsic':
            scanImage.style.width = 'auto';
            scanImage.style.height = 'auto';
            break;
        default: // must be a number
            // use percentage of 1000 pixels
            // although naturalWidth could be better a new image reports 0
            // if it is slow to load
            scanImage.style.width = (10 * zoom) + 'px';
            scanImage.style.height = 'auto';
        }
    }

    function setProfileName() {
        $("#profile-name").text(settings.profileName);
    }

    function copyProfile(newProfileName) {
        profile.ratio = splitControl.getRatio();
        settings.profiles[newProfileName] = deepCopy(settings.profiles[newProfileName], profile);
        settings.profileName = newProfileName;
//        setProfileName();
        saveSettings();
    }

    function projectPagePath() {
        return 'v1/project/' + projectID + "/state/" + projState + "/page/" + imageID + "/state/" + pageState;
    }

    function setupProfile() {
        // make a copy so if we do a 'save as' the original will not be changed
        profile = deepCopy(profile, settings.profiles[settings.profileName]);
        setProfileName();
        setZoom();
        setupFontFamilySelectors();
        setTextFontFamily();
        setupFontSize();
        setColors();
        setupIcons();
        $('#show_icons').prop("checked", profile.showIcons);
        setWrap();
        $("#wrap_text").prop("checked", profile.wrap);
        splitControl.setSplit(profile.split, profile.ratio);
        splitButtonsSetup();
        imageUrl = projectsUrl + projectID + "/";
    }

    function loadPage() {
        if (imageID) {
            // check out a done or inprogress page
            $.get(apiUrl, {'q': projectPagePath() + "/action/checkoutpage"}, loadImageText);
        } else {
            // checkout a new page
            $.post(apiUrl, {'q': 'v1/project/' + projectID + "/state/" + projState + "/action/checkoutnextpage"}, loadImageText);
        }
    }

    function loadSettings(data) {
        settings = deepCopy(settings, JSON.parse(data.settings), true);
        console.log(settings);
        setupProfile();
        loadPage();
    }

    function saveSettings() {
        $.post(apiUrl, {'q': 'v1/settings/put', 'data': JSON.stringify(settings)});
    }

    function setupKeyboard(data) {
//            console.log(data);
        picker.loadKb(data.keyboards);
        $.get(apiUrl, {'q': 'v1/settings/get'}, loadSettings);
    }

    function closeDropDowns() {
        $(".proof-menu-content").addClass('nodisp');
        $(window).unbind("click keydown");
    }

    if (!Element.prototype.matches) {
        Element.prototype.matches = Element.prototype.msMatchesSelector;
    }

    function closeOnClick(event) {
        // do not close if click on the button or it will never appear
        if (!event.target.matches('.dropdown_button')) {
            closeDropDowns();
//            $(window).unbind("click keydown");
        }
    }

    function closeOnClickOutside(event) {
        // do not close if click on the button or menu box
        if (!event.target.matches('.dropdown *')) {
            closeDropDowns();
//            $(window).unbind("click keydown");
            return false;
        }
    }

    function escapeDropDowns(event) {
        if (event.keyCode === 27) {
            closeDropDowns();
//            $(window).unbind("click keydown");
        }
    }

    // get key data first because it affects height of toolbox, before split setup
    $.get(apiUrl, {'q': 'v1/project/' + projectID + "/action/keydata"}, setupKeyboard);

    proofControl = {
        zoomImage: function (ratio) {
            // find new width as percentage of 1000 px
            profile.zoom = Math.floor((scanImage.width * ratio) / 10);
            setZoom();
        },

        sizeImage: function (code) {
            profile.zoom = code;
            setZoom();
        },

        setSplit: function (mode) {
            profile.split = mode;
            splitButtonsSetup();
            splitControl.setSplit(mode, 0.5);
        },

        showProfileMenu: function() {
            closeDropDowns();
            $("#profile_menu").removeClass('nodisp');
            $(window).click(closeOnClickOutside);
            $(window).keydown(escapeDropDowns);
            drawProfileList();
        },

        showMenu: function (id, mode) {
            closeDropDowns();
            $("#" + id).removeClass('nodisp');
            switch (mode) {
            case 1:
                $(window).click(closeOnClickOutside);
                break;
            case 2:
                $(window).click(closeOnClick);
                break;
            }
            $(window).keydown(escapeDropDowns);
        },

        iconControl: function (control) {
            profile.showIcons = control.checked;
            setupIcons();
            splitControl.reLayout(); // height of control bar may be changed
        },

        wrapControl: function (control) {
            profile.wrap = control.checked;
            setWrap();
        },

        setTextColor: function (control) {
            profile.textColor = control.value;
            textArea.css("color", profile.textColor);
        },

        setBackColor: function (control) {
            profile.textBackground = control.value;
            textArea.css("backgroundColor", profile.textBackground);
        },

        setImageBackColor: function (control) {
            profile.imageBackground = control.value;
            imageDiv.css("backgroundColor", profile.imageBackground);
        },

        restoreColors: function () {
            profile.imageBackground = defaultStyle.imageBackground;
            profile.textColor = defaultStyle.textColor;
            profile.textBackground = defaultStyle.textBackground;
            setColors();
        },

        addFont: function () {
            var newFont = $('#new-font').val();
            if (newFont !== "") {
                settings.fonts[newFont] = 0;
                setupFontFamilySelectors();
            }
        },

        removeFont: function () {
            var font = removeFontSelector.value;
            if (font === profile.fontFamily) {
                alert("Cannot delete the current font");
                return;
            }
            if (confirm(sprintf("Are you sure you want to delete %s?", font))) {
                delete settings.fonts[font];
                setupFontFamilySelectors();
            }
        },

        selectProfile: function() {
            var profileName = $("#list-select-profiles").val();
            settings.profileName = profileName;
            closeDropDowns();
            setupProfile();
        },

        saveProfile: function () {
            closeDropDowns();
            copyProfile(settings.profileName);
        },

        saveNewProfile: function () {
            var newProfileName = $('#new-profile').val();
            if (newProfileName === "") {
                return;
            }
            closeDropDowns();
            copyProfile(newProfileName);
        },

        saveAsProfile: function () {
            var newProfileName = $("#list-save-profiles").val();
            closeDropDowns();
            copyProfile(newProfileName);
        },

        deleteProfile: function () {
            var profileName = $("#list-delete-profiles").val();
            if(confirm(sprintf("Are you sure you want to delete %s?", profileName))) {
                delete settings.profiles[profileName];
            }
            closeDropDowns();
            saveSettings();
        },

        changeFontFamily: function (selector) {
            profile.fontFamily = selector.value;
            setTextFontFamily();
        },

        changeFontSize: function (selector) {
            profile.fontSize = selector.value;
            setTextFontSize();
        },

        revertToOriginal: function () {
            if (confirm(messages.confirmRevertOrig)) {
                $.post(apiUrl, {'q': projectPagePath() + "/action/reverttoorig", 'text-data': textArea.val()}, loadText);
            }
        },

        revertToLastSave: function () {
            if (confirm(messages.confirmRevertToLastSave)) {
                $.get(apiUrl, {'q': projectPagePath() + "/action/reverttolastsave"}, loadText);
            }
        },

        saveAsInProgress: function () {
            $.post(apiUrl, {'q': projectPagePath() + "/action/saveasinprogress", 'text-data': textArea.val()}, loadState);
        },

        saveAndDoNext: function () {
            $.post(apiUrl, {'q': projectPagePath() + "/action/saveanddonext", 'text-data': textArea.val()}, loadImageText);
        },

        saveAsDone: function () {
            $.post(apiUrl, {'q': projectPagePath() + "/action/saveasdone", 'text-data': textArea.val()}, toProjectPage);
        },

        returnPage: function () {
            if (confirm(messages.confirmReturn)) {
                $.get(apiUrl, {'q': projectPagePath() + "/action/returnpage"}, toProjectPage);
            }
        },

        stopProof: function () {
            if (confirm(messages.confirmStop)) {
                toProjectPage();
            }
        },

        reportBadPage: function () {
//            window.location.replace(codeUrl + "tools/proofers/report_bad_page.php?id=" + projectID + "&expected_state=" + projState);
            window.location = codeUrl + "tools/proofers/report_bad_page.php?projectid=" + projectID + "&proj_state=" + projState + "&imagefile=" + imageID + "&page_state=" + pageState;
        }
    };
});
