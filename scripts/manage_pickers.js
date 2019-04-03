/*global document window codeUrl $ apiUrl confirm sprintf confirmDelete pickers*/

$(function () {
    "use strict";
    var selector = document.getElementById("codes");

    function drawSelectorSet(data) {
        $("#selector-set").val(data.string);
    }

    $.getJSON(apiUrl, {"q": "v1/config/pickerset"}, drawSelectorSet);

    $("#edit-button").click(function () {
        window.location = codeUrl + "tools/edit_char_selector.php?action=edit&code=" + encodeURIComponent(selector.value);
    });

    $("#delete-button").click(function () {
        if (confirm(sprintf(confirmDelete, selector.value))) {
            $.post(apiUrl, {"q": "v1/picker/delete", "code": selector.value}, pickers.drawCodes);
        }
    });

    $("#new-button").click(function () {
        window.location = codeUrl + "tools/edit_char_selector.php?action=new";
    });

    $("#save-set").click(function () {
        $.post(apiUrl, {"q": "v1/config/pickerset", "data": $("#selector-set").val()});
    });

});
