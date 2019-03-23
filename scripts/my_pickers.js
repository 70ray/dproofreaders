/*global document $ apiUrl */

$(function () {
    "use strict";
    var selector = document.getElementById("codes");

    function fillForm(data) {
        var picker = data.picker;
        $("#upper-row").val(picker.upper);
        $("#lower-row").val(picker.lower);
    }

    function getPicker() {
        $.getJSON(apiUrl, {"q": "v1/picker", "code": selector.value}, fillForm);
    }

    function drawSelector(data) {
        // populate selector
        // empty it first
        while (selector.length) {
            selector.remove(0);
        }
        data.codes.forEach(function (code) {
            var opt = document.createElement("option");
            opt.value = code;
            opt.text = code;
            selector.add(opt);
        });
        getPicker();
    }

    function drawMySet(data) {
        $("#my-set").val(data.string);
    }

    function getCodes() {
        $.get(apiUrl, {"q": "v1/pickers/list"}, drawSelector);
    }

    getCodes();
    $.getJSON(apiUrl, {"q": "v1/user/pickerset"}, drawMySet);

    $("#codes").change(function () {
        getPicker();
    });

    $("#save-set").click(function () {
        $.post(apiUrl, {"q": "v1/user/pickerset", "data": $("#my-set").val()});
    });

});
