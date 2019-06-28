/*global $ apiUrl projectID*/

$(function () {
    "use strict";
    var selector = document.getElementById("codes");

    function drawSelectorSet(data) {
        $("#selector-set").val(data.string);
    }

    $.getJSON(apiUrl, {"q": "v1/project/" + projectID + "/pickerset"}, drawSelectorSet);

    $("#append").click(function () {
        var selectorSet = $("#selector-set");
        selectorSet.val(selectorSet.val() + " " + selector.value);
    });

    $("#save-set").click(function () {
        $.post(apiUrl, {"q": "v1/project/" + projectID + "/pickerset", "data": $("#selector-set").val()});
    });

});
