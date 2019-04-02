/*global document window codeUrl $ apiUrl confirm sprintf confirmDelete */

$(function () {
    "use strict";
    var selector = document.getElementById("codes");

    function fillForm(data) {
        var picker = data.picker;
        if(picker) {
            $("#upper-row").val(picker.upper);
            $("#lower-row").val(picker.lower);
        }
    }

    function getPicker() {
        var pickerCode = selector.value;
        // if there are no pickers defined it will be null
        if(pickerCode) {
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

    function drawDefaultSet(data) {
        $("#def-set").val(data.string);
    }

    function getCodes() {
        $.get(apiUrl, {"q": "v1/pickers/list"}, drawSelector);
    }

    getCodes();
    $.getJSON(apiUrl, {"q": "v1/config/pickerset"}, drawDefaultSet);

    $("#codes").change(function () {
        getPicker();
    });

    $("#edit-button").click(function () {
        window.location = codeUrl + "tools/edit_char_selector.php?action=edit&code=" + encodeURIComponent(selector.value);
    });

    $("#delete-button").click(function () {
        if (confirm(sprintf(confirmDelete, selector.value))) {
            $.post(apiUrl, {"q": "v1/picker/delete", "code": selector.value}, getCodes);
        }
    });

    $("#new-button").click(function () {
        window.location = codeUrl + "tools/edit_char_selector.php?action=new";
    });

    $("#save-set").click(function () {
        $.post(apiUrl, {"q": "v1/config/pickerset", "data": $("#def-set").val()});
    });

});
