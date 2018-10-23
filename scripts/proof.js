/*global document window Image projectsUrl alert codeUrl imageFile messages
 $ apiUrl projectID projState imageID pageState confirm textControl picker
initSplit */

var proofControl;
function initProofControl() {
    "use strict";
    proofControl = (function () {
        var scanImage = document.getElementById("scanimage");
        var textArea = document.getElementById("text_area");
        var imageUrl;
        var splitControl;
//            splitControl = initSplit(1, 0.5);

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
            textArea.value = data.text;
            textArea.focus();
            textControl.setCaret(0);
        }

        function loadImageText(data) {
    console.log(data);
            imageID = data.imageID;
            scanImage.src = imageUrl + data.imageID;
            scanImage.alt = data.imageID;
            loadText(data);
            setupToolbox(data.isFormattingRound);
        }

        function toProjectPage() {
            window.location.replace(codeUrl + "project.php?id=" + projectID + "&expected_state=" + projState);
        }

        function projectPagePath() {
            return 'v1/project/' + projectID + "/state/" + projState + "/page/" + imageID + "/state/" + pageState;
        }

        function setup2(data) {
            console.log(data);
            var vSplit = data.split;
            splitControl = initSplit(vSplit, 0.5);
            splitButtonsSetup(vSplit)
            imageUrl = projectsUrl + projectID + "/";
            if (imageID) {
                // check out a done or inprogress page
                $.get(apiUrl, {'q': projectPagePath() + "/checkoutpage"}, loadImageText);
            } else {
                // checkout a new page
                $.post(apiUrl, {'q': 'v1/project/' + projectID + "/state/" + projState + "/checkoutnextpage"}, loadImageText);
            }
        }

        function setup1(data) {
            console.log(data);
            picker.loadKb(data);
            $.get(apiUrl, {'q': 'v1/settings/proofing/get'}, setup2);
        }

        function splitButtonsSetup(mode) // 1=vertical, 0=horizontal 
        {
            var vButton = $(".v_split", ".control-div");
            var hButton = $(".h_split", ".control-div");
            if(mode) {
                vButton.show();
                hButton.hide();
            } else {
                vButton.hide();
                hButton.show();
            }
        }

        // get key datat first because it affects height of toolbox, before split setup
        $.get(apiUrl, {'q': 'v1/project/' + projectID + "/keydata"}, setup1);

        return {
            setSplit: function (mode) {
                splitButtonsSetup(mode);
                splitControl.setSplit(mode);
            },

            revertToOriginal: function () {
                if (confirm(messages.confirmRevertOrig)) {
                    $.post(apiUrl, {'q': projectPagePath() + "/reverttoorig", 'text-data': textArea.value}, loadText);
                }
            },

            revertToLastSave: function () {
                if (confirm(messages.confirmRevertToLastSave)) {
                    $.get(apiUrl, {'q': projectPagePath() + "/reverttolastsave"}, loadText);
                }
            },

            saveAsInProgress: function () {
                $.post(apiUrl, {'q': projectPagePath() + "/saveasinprogress", 'text-data': textArea.value}, loadState);
            },

            saveAndDoNext: function () {
                $.post(apiUrl, {'q': projectPagePath() + "/saveanddonext", 'text-data': textArea.value}, loadImageText);
            },

            saveAsDone: function () {
                $.post(apiUrl, {'q': projectPagePath() + "/saveasdone", 'text-data': textArea.value}, toProjectPage);
            },

            returnPage: function () {
                if (confirm(messages.confirmReturn)) {
                    $.get(apiUrl, {'q': projectPagePath() + "/returnpage"}, toProjectPage);
                }
            },

            stopProof: function () {
                if (confirm(messages.confirmStop)) {
                    toProjectPage();
                }
            }
        };
    }());
}

window.addEventListener("DOMContentLoaded", initProofControl, false);
