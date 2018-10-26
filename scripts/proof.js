/*global document window Image projectsUrl alert codeUrl imageFile messages
 $ apiUrl projectID projState imageID pageState confirm textControl picker
initSplit Element */

var proofControl;
$(function () {
    "use strict";
    var scanImage = document.getElementById("scanimage");
    var textArea = $("#text_area");
    var imageDiv = $("#imagedisplay");
    var imageUrl;
    var splitControl;
    var proofStyle = {};

    var defaultStyle = {
        split: 1,
        imageBackground: "#CDC0B0",
        textColor: "#000000",
        textBackground: "#ffffff",
        showIcons: true
    };

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

    function copy(dest, source) {
        var key;
        for (key in source) {
            dest[key] = source[key];
        }
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

    function projectPagePath() {
        return 'v1/project/' + projectID + "/state/" + projState + "/page/" + imageID + "/state/" + pageState;
    }

    function setupProfile(data) {
//        console.log(data.settings)
        copy(proofStyle, defaultStyle);
        copy(proofStyle, JSON.parse(data.settings));
//        console.log(proofStyle);
//        console.log(proofStyle.showIcons);
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

        saveProfile: function () {
//            console.log(proofStyle);
//            console.log(JSON.stringify(proofStyle));
            $.post(apiUrl, {'q': 'v1/settings/put', 'data': JSON.stringify(proofStyle)});
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
