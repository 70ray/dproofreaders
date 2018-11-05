/*global window codeUrl $ apiUrl projectID projState timeCutoff */

var sagws;
$(function () {
    "use strict";

    function listProjects(projectsData) {
        console.log(projectsData);
        var projects = projectsData.projects;
        var numProjects = projects.length;
        var projectData;
        var projectid;
        if (0 === numProjects) {
            $("#project_data").html("There are no projects");
            return;
        }
        $("#project_data").html("");
        var projectsNeedingAttention = 0;
        var i = 0;

        function loadProject(data) {
            // this gets called at least twice, first time with no data
            // last time do not call again
            console.log(data);
            console.log(i);
            var projHTML = '';

            if (data) {
                var projectName = projectData[1];
                var projectState = projectData[2];
            console.log(projectName);
                var suggestions = data.goodWordData;
                if (suggestions.length !== 0) {
                    projectsNeedingAttention += 1;

                    projHTML += "<hr><h3>" + projectName + "</h3>";
                    projHTML += "<p><b>" + "State" + ": </b>" + projectState + "</p>";

                    projHTML += "<button type='button' onclick='sagws.checkAll(\"" + projectid + "\", true);'>Select all</button>";
                    projHTML += "<button type='button' onclick='sagws.checkAll(\"" + projectid + "\", false);'>Unselect all</button>";

                    projHTML += "<table class='basic striped'><tr><th>Word</th><th>Freq</th><th>Sugg</th><th>Show Context</th></tr>";

                    suggestions.forEach(function (suggestion) {
                        var word = suggestion[0];
                        projHTML += "<tr><td class='mono'><input type='checkbox' class='" + projectid + "' value='" + word + "'> " + word + "</td>" +
                                "<td class='right-align'>" + suggestion[1] + "</td><td style='text-align: right;'>" + suggestion[2] + "</td>" +
                                "<td><a href='show_good_word_suggestions_detail.php?projectid=" + projectid + "&amp;word=637574696f6e&amp;timeCutoff=0' target='detailframe'>Context</a></td></tr>";
                    });
                    projHTML += "</table>";
                    console.log(projHTML);
                    $("#project_data").append(projHTML);
                }
                i += 1;
                if (i === numProjects) {
                    if (0 === projectsNeedingAttention) {
                        $("#project_data").html("No projects have proofreader suggestions for the given timeframe.");
                    }
                    return;
                }
            }
            projectData = projects[i];
            projectid = projectData[0];
            $.getJSON(apiUrl, {'q': 'v1/project/' + projectid + "/action/good_word_suggestions"}, loadProject);
        }

        loadProject();
    }
/*
    function setTimeCutoffText() {
        var timeCutoffText;
        if (timeCutoff === -1) {
            timeCutoffText = messages.lastMod;
        } else if (timeCutoff === 0) {
            timeCutoffText = messages.allSuggestions;
        } else {
            timeCutoffText = "placeholder";//sprintf(messages.pastDays, strftime($datetime_format,$timeCutoff));
        }
    }
*/
    sagws = {
        show: function () {
            // get projects for user
            var username = $("#pm_name").val();
            $.getJSON(apiUrl, {'q': 'v1/user/' + username + "/action/projects"}, listProjects);
        },

        checkAll: function (boxClass, trueOrFalse) {
            $("." + boxClass).prop("checked", trueOrFalse);
        }
    };
});
