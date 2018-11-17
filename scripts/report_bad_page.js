/*global $ document alert apiUrl */

var badPageControl;

$(function () {
    "use strict";
    var reasonSelector;
    function setupSelector(data) {
        console.log(data);
        reasonSelector = document.getElementById("badness-reason");
        data.reasons.forEach(function (reason, index) {
            var opt = document.createElement("option");
            opt.value = index;
            opt.text = reason;
            reasonSelector.add(opt);
        });
    }

    function checkState (data) {
//        console.log(data);
        if (data.projectBad) {
            alert(messages.markedAsBad);
            window.location.replace(codeUrl + "activity_hub.php");
        } else {
            alert(messages.reportSubmitted);
            window.location.replace(codeUrl + "project.php?id=" + projectID + "&expected_state=" + projState);
        }
    }

    badPageControl = {
        report: function () {
            if (reasonSelector.selectedIndex === 0) {
                alert(messages.selectReason);
                return;
            }
            $.post(apiUrl, {'q': 'v1/project/' + projectID + "/state/" + projState + "/page/" + imageID + "/state/" + pageState + "/action/reportbad",  'reason': reasonSelector.selectedIndex}, checkState);
        }
    };

    requireLogin().then(function () {
        $.get(apiUrl, {"q": "v1/constants/page_badness_reasons"}, setupSelector);
    });
});