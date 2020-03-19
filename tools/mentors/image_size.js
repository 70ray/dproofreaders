/*global $ */
$(function () {
    "use strict";
    var image = $("#image");

    function changeZoom() {
        image.width(10 * $("#percent").val());
        image.height("auto");
    }

    $("#resize").click(function () {
        changeZoom();
    });
});
