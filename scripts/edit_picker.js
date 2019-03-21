/*global $ codeUrl apiUrl window action code*/

$(function () {
    "use strict";
    function goBack() {
        window.location = codeUrl + "tools/manage_char_selectors.php";
    }

    $("#cancel").click(goBack);

    $("#save").click(function () {
        var pickerData = {
            code: $("#code").val(),
            upper: $("#upper-row").val(),
            lower: $("#lower-row").val()
        };
        if (action === "new") {
            $.post(apiUrl, {"q": "v1/picker/insert", "data": JSON.stringify(pickerData)}, goBack);
        } else {
            $.post(apiUrl, {"q": "v1/picker/replace", "data": JSON.stringify(pickerData)}, goBack);
        }
    });

    function fillForm(data) {
        var picker = data.picker;
        $("#upper-row").val(picker.upper);
        $("#lower-row").val(picker.lower);
    }

    if (action === "edit") {
        $("#code").val(code);
        $.getJSON(apiUrl, {"q": "v1/picker", "code": code}, fillForm);
    }
});
