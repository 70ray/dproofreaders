/*global $ alert */

$( document ).ajaxError(function( event, jqxhr, settings, thrownError ) {
    alert("HTTP status: " + jqxhr.status + "\n" + jqxhr.responseJSON.error);
});
