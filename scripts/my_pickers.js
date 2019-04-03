/*global $ apiUrl */

$(function () {
    "use strict";

    function drawSelectorSet(data) {
        $("#selector-set").val(data.string);
    }

    $.getJSON(apiUrl, {"q": "v1/user/pickerset"}, drawSelectorSet);

    $("#save-set").click(function () {
        $.post(apiUrl, {"q": "v1/user/pickerset", "data": $("#selector-set").val()});
    });

});
