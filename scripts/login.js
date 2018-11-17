/*global $ alert apiUrl window origin messages */

var signIn;
$(function () {
    "use strict";
    function checkLogin(data) {
        if (!data.loggedIn) {
            alert("login failed");
        } else {
            history.back();
        }
    }

    signIn = function () {
        var userName = $("#loginform-userNM").val();
        var pw = $("#loginform-userPW").val();
        $.post(apiUrl, {'q': 'v1/login/' + userName, 'userPW': pw}, checkLogin);
    };
});
