/*global window codeUrl $ apiUrl projectID projState */

var projectControl;
$(function () {
    "use strict";

    function toProofPage(data) {
        window.location = codeUrl + "tools/proofers/proof.php?projectid=" + projectID + "&proj_state=" + projState + "&imagefile=" + data.imageID + "&page_state=" + data.pageState;
    }

    projectControl = {
        startProofReading: function () {
            // checkout a new page
            $.post(apiUrl, {'q': 'v1/project/' + projectID + "/state/" + projState + "/action/checkoutnextpage"}, toProofPage);
        }
    };
});
