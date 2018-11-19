/*global window codeUrl $ apiUrl */

var projectControl;
$(function () {
    "use strict";
    projectControl = {
        startProofReading: function (projectID, projState) {
            // checkout a new page
            $.post(apiUrl, {'q': 'v1/project/' + projectID + "/state/" + projState + "/action/checkoutnextpage"}).done(function (data) {
                if(data.message) {
                    alert(data.message);
                } else {
                    window.location = codeUrl + "tools/proofers/proof.php?projectid=" + projectID + "&proj_state=" + projState + "&imagefile=" + data.imageID + "&page_state=" + data.pageState;
                }
            });
        }
    };
});
