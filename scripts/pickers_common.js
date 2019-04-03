/*global document $ apiUrl */
var pickers;
$(function () {
    "use strict";
    var selector = document.getElementById("codes");

    function fillForm(data) {
        var picker = data.picker;
        if (picker) {
            $("#upper-row").val(picker.upper);
            $("#lower-row").val(picker.lower);
        }
    }

    function getPicker() {
        var pickerCode = selector.value;
        // if there are no pickers defined it will be null
        if (pickerCode) {
            $.getJSON(apiUrl, {"q": "v1/picker", "code": pickerCode}, fillForm);
        }
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

    function getCodes() {
        $.get(apiUrl, {"q": "v1/pickers/list"}, drawSelector);
    }

    getCodes();

    pickers = {drawCodes: getCodes};

    $("#codes").change(function () {
        getPicker();
    });
});
