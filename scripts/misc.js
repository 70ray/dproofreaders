/*global codeUrl */

function sprintf(string, p1) {
    "use strict";
    return string.replace("%s", p1);
}

function makeProofURL(projectID, projState, imageID, pageState) {
    "use strict";
    return codeUrl + "tools/proofers/proof.php?projectid=" + projectID + "&proj_state=" + projState + "&imagefile=" + imageID + "&page_state=" + pageState;
}
