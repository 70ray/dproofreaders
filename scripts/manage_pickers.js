/*global document window codeUrl $ apiUrl confirm sprintf confirmDelete */

$(function () {
    "use strict";
    var selector = document.getElementById("codes");

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
    }

    function drawDefaultSet(data) {
        $("#def-set").val(data.string);
    }

    function getCodes() {
        $.get(apiUrl, {'q': 'v1/pickers/list'}, drawSelector);
    }

    getCodes();
    $.getJSON(apiUrl, {'q': 'v1/config/pickerset'}, drawDefaultSet);

    $("#edit-button").click(function () {
        window.location = codeUrl + "tools/edit_char_selector.php?action=edit&code=" + encodeURIComponent(selector.value);
    });

    $("#delete-button").click(function () {
        if (confirm(sprintf(confirmDelete, selector.value))) {
            $.post(apiUrl, {'q': 'v1/picker/delete', 'code': selector.value}, getCodes);
        }
    });

    $("#new-button").click(function () {
        window.location = codeUrl + "tools/edit_char_selector.php?action=new";
    });

    $("#save-set").click(function () {
        $.post(apiUrl, {'q': 'v1/config/pickerset', 'data': $("#def-set").val()});
    });

});
