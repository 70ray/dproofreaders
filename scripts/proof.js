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
    var splitControl;
    var proofStyle = {};
    var settings = {};

    var defaultSettings = {
        profiles: {
            profile_0: {
                split: 1,
                imageBackground: "#CDC0B0",
                textColor: "#000000",
                textBackground: "#ffffff",
                showIcons: true,
                fontFamily: 'monospace',
                fontSize: '14px',
                zoom: 100
            }
        },
        profile: "profile_0",
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
            if (!keep) {
                dest = Array.isArray(source) ? [] : {};
            }
            if (dest) {
                for (i in source) {
                    dest[i] = deepCopy(dest[i], source[i], keep);
                }
            }
        } else {
            dest = source;
        }
        return dest;
    }

    function setColors() {
        imageDiv.css("backgroundColor", proofStyle.imageBackground);
        $('#image_back_color').val(proofStyle.imageBackground);
        textArea.css('color', proofStyle.textColor);
        $('#text_color').val(proofStyle.textColor);
        textArea.css('backgroundColor', proofStyle.textBackground);
        $('#back_color').val(proofStyle.textBackground);
    }

    function setupIcons() {
        $('#icon_bar')[proofStyle.showIcons ? 'show' : 'hide']();
    }

    function splitButtonsSetup() {
        var mode = proofStyle.split;
        $(".v_split", ".control-div")[mode ? 'show' : 'hide']();
        $(".h_split", ".control-div")[mode ? 'hide' : 'show']();
    }

    function setTextFontFamily() {
        textArea.css("fontFamily", proofStyle.fontFamily);
    }

    function setupSelector(selector, optionList, selectedOption) {
        // empty it first
        while (selector.length) {
            selector.remove(0);
        }
        optionList.forEach(function (item) {
            var opt = document.createElement("option");
            opt.value = item;
            opt.text = item;
            if (item === selectedOption) {
                opt.selected = true;
            }
            selector.add(opt);
        });
    }



    function setupFontFamilySelectors() {
        var optionList = [];
        var font;
        for (font in settings.fonts) {
            optionList.push(font);
        }
        optionList.sort();
        setupSelector(fontSelector, optionList, proofStyle.fontFamily);
        setupSelector(removeFontSelector, optionList);
    }

    function setTextFontSize() {
        textArea.css("font-size", proofStyle.fontSize);
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
            if (sizeString === proofStyle.fontSize) {
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

    function setZoom() {
        var zoom = proofStyle.zoom;
        console.log(zoom);
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

    function projectPagePath() {
        return 'v1/project/' + projectID + "/state/" + projState + "/page/" + imageID + "/state/" + pageState;
    }

    function setupProfile(data) {
        settings = deepCopy(settings, defaultSettings, false);
        settings = deepCopy(settings, JSON.parse(data.settings), true);
        console.log(settings);
        proofStyle = settings.profiles[settings.profile];
        setZoom();
        setupFontFamilySelectors();
        setTextFontFamily();
        setupFontSize();
        setColors();
        setupIcons();
        $('#show_icons').prop("checked", proofStyle.showIcons);
        splitControl = initSplit(proofStyle.split, 0.5);
        splitButtonsSetup();
        imageUrl = projectsUrl + projectID + "/";
        if (imageID) {
            // check out a done or inprogress page
            $.get(apiUrl, {'q': projectPagePath() + "/action/checkoutpage"}, loadImageText);
        } else {
            // checkout a new page
            $.post(apiUrl, {'q': 'v1/project/' + projectID + "/state/" + projState + "/action/checkoutnextpage"}, loadImageText);
        }
    }

    function setupKeyboard(data) {
//            console.log(data);
        picker.loadKb(data.keyboards);
        $.get(apiUrl, {'q': 'v1/settings/get'}, setupProfile);
    }

    function closeDropDowns() {
        $(".proof-menu-content").addClass('nodisp');
    }

    if (!Element.prototype.matches) {
        Element.prototype.matches = Element.prototype.msMatchesSelector;
    }

    function closeOnClick(event) {
        // do not close if click on the button or it will never appear
        if (!event.target.matches('.dropdown_button')) {
            closeDropDowns();
            $(window).unbind("click keydown");
        }
    }

    function closeOnClickOutside(event) {
        // do not close if click on the button or menu box
        if (!event.target.matches('.dropdown *')) {
            closeDropDowns();
            $(window).unbind("click keydown");
            return false;
        }
    }

    function escapeDropDowns(event) {
        if (event.keyCode === 27) {
            closeDropDowns();
            $(window).unbind("click keydown");
        }
    }

    // get key data first because it affects height of toolbox, before split setup
    $.get(apiUrl, {'q': 'v1/project/' + projectID + "/action/keydata"}, setupKeyboard);

    proofControl = {
        zoomImage: function (ratio) {
            // find new width as percentage of 1000 px
            proofStyle.zoom = Math.floor((scanImage.width * ratio) / 10);
            setZoom();
        },

        sizeImage: function (code) {
            proofStyle.zoom = code;
            setZoom();
        },

        setSplit: function (mode) {
            proofStyle.split = mode;
            splitButtonsSetup();
            splitControl.setSplit(mode);
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
            proofStyle.showIcons = control.checked;
            setupIcons();
            splitControl.reLayout(); // height of control bar may be changed
        },

        setTextColor: function (control) {
            proofStyle.textColor = control.value;
            textArea.css("color", proofStyle.textColor);
        },

        setBackColor: function (control) {
            proofStyle.textBackground = control.value;
            textArea.css("backgroundColor", proofStyle.textBackground);
        },

        setImageBackColor: function (control) {
            proofStyle.imageBackground = control.value;
            imageDiv.css("backgroundColor", proofStyle.imageBackground);
        },

        restoreColors: function () {
            proofStyle.imageBackground = defaultStyle.imageBackground;
            proofStyle.textColor = defaultStyle.textColor;
            proofStyle.textBackground = defaultStyle.textBackground;
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
            if (font === proofStyle.fontFamily) {
                alert("Cannot delete the current font");
                return;
            }
            if (confirm(sprintf("delete font %s?", font))) {
                delete settings.fonts[font];
                setupFontFamilySelectors();
            }
        },

        saveProfile: function () {
//            console.log(proofStyle);
//            console.log(JSON.stringify(proofStyle));
            $.post(apiUrl, {'q': 'v1/settings/put', 'data': JSON.stringify(settings)});
        },

        changeFontFamily: function (selector) {
            proofStyle.fontFamily = selector.value;
            setTextFontFamily();
        },

        changeFontSize: function (selector) {
            proofStyle.fontSize = selector.value;
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
