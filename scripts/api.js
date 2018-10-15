/*global $ alert */

$( document ).ajaxError(function( event, jqxhr, settings, thrownError ) {
    console.log(event);
    alert("HTTP status: " + jqxhr.status + "\n" + jqxhr.responseJSON.error);
});
