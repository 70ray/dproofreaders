/*global window alert codeUrl messages $ apiUrl location noLogin */

function requireLogin() {
    "use strict";
    var df = $.Deferred();
    function checkLogin(data) {
        if (data.loggedIn) {
            df.resolve();
        } else {
            alert(noLogin);
            window.location = codeUrl + "accounts/application_login.php";
        }
    }
    $.getJSON(apiUrl, {'q': 'v1/userisloggedin'}, checkLogin);
    return df;
}
