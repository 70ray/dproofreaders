/*global $ apiUrl projectID*/

$(function () {
    "use strict";

    function drawSelectorSet(data) {
        $("#selector-set").val(data.string);
    }

    $.getJSON(apiUrl, {"q": "v1/project/" + projectID + "/pickerset"}, drawSelectorSet);

    $("#save-set").click(function () {
        $.post(apiUrl, {"q": "v1/project/" + projectID + "/pickerset", "data": $("#selector-set").val()});
    });

});
