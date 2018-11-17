/*global $ apiUrl messages splitControl requireLogin */
var sagws;
$(function () {
    "use strict";

    function setFreqCutoff(ancestor) {
        var freqCutoff = parseInt($("#freq_select").val());
        $(ancestor + " .frequency").each(function () {
            var cell = $(this);
            var row = cell.parent();
            if (parseInt(cell.text()) < freqCutoff) {
                row.hide();
            } else {
                row.show();
            }
        });
    }

// perhaps better to use encodeURIComponent(word) for utf8
    function encodeWord(word) {
        var result = '';
        var i;
        for (i = 0; i < word.length; i += 1) {
            result += word.charCodeAt(i).toString(16);
        }
        return result;
    }

    function drawTable(data, projectid) {
        var tabHTML = "<table class='basic striped'><tr><th>" + messages.word + "</th><th>Freq</th><th>" + messages.sugg + "</th><th>" + messages.showContext + "</th></tr>";
        // assume word can contain ' but not "
        data.goodWordData.forEach(function (suggestion) {
            var word = suggestion[0];
            var encWord = encodeWord(word);
            tabHTML += '<tr><td class="mono"><input type="checkbox" class="cb' + projectid + '" value="' + word + '"> ' + word + '</td>' +
                    "<td class='right-align frequency'>" + suggestion[1] + "</td><td style='text-align: right;'>" + suggestion[2] + "</td>" +
                    "<td><a href='show_good_word_suggestions_detail.php?projectid=" + projectid + "&amp;word=" + encWord + "&amp;timeCutoff=" + data.timeCutoffActual + "' target='detailframe'>" + messages.context + "</a></td></tr>";
        });

        tabHTML += "</table>";
        return tabHTML;
    }

    function drawProjectData(projectData, timeCutoffDays) {
        var projHTML = '';
        var projectid = projectData[0];
        $.getJSON(apiUrl, {'q': 'v1/project/' + projectid + "/action/good_word_suggestions", 'days': timeCutoffDays}).done(function (data) {
//            console.log(data);
            if (data.goodWordData.length !== 0) {
                // hide message about no projects needing attention
                $("#no-suggestions").hide();
                projHTML += "<hr><h3>" + projectData[1] + "</h3>";
                projHTML += "<p><b>" + messages.state + ": </b>" + projectData[2] + "</p>";

                projHTML += "<p><button type='button' onclick='sagws.checkAll(\"" + projectid + "\", true);'>" + messages.selectAll + "</button> <button type='button' onclick='sagws.checkAll(\"" + projectid + "\", false);'>" + messages.unSelectAll + "</button></p>";

                projHTML += "<div id='tab_" + projectid + "'>";
                projHTML += drawTable(data, projectid);
                projHTML += "</div>";
                projHTML += "<p><button type='button' onclick='sagws.addWords(\"" + projectid + "\");'>" + messages.submitLabel + "</button></p>";

                $("#" + projectid).html(projHTML);
                setFreqCutoff("#tab_" + projectid);
            }
        });
    }

    function listProjects(projectsData) {
        var projects = projectsData.projects;
        var numProjects = projects.length;
        if (0 === numProjects) {
            $("#project_data").html("There are no projects");
            return;
        }
        // make a division for each project
        var projectid;
        var projHTML = '';
        projects.forEach(function (projectData) {
            projectid = projectData[0];
            projHTML += "<div id='" + projectid + "'></div>";
        });
        $("#project_data").html(projHTML);

        var timeCutoffDays = $("#time-cutoff").val(); // this is a string
        $("#no-suggestions").show(); // will be hidden again if any project data
        // load each project data into its div
        projects.forEach(function (projectData) {
            drawProjectData(projectData, timeCutoffDays);
        });
    }

    splitControl.setSplit(1, 0.3);

    requireLogin().then(function () {
        $.getJSON(apiUrl, {'q': 'v1/useris/project_facilitator'}).done(function (data) {
            $("#pm_name").val(data.name);
            if (data.project_facilitator) {
                $("#input-user").show();
            }
        });
    });

    sagws = {
        show: function () {
            // get projects for user
            var pmName = $("#pm_name").val();
            $.getJSON(apiUrl, {'q': 'v1/user/' + pmName + "/action/projects"}, listProjects);
        },

        checkAll: function (projectid, trueOrFalse) {
            $(".cb" + projectid).prop("checked", trueOrFalse);
        },

        addWords: function (projectid) {
            var wordList = [];
            $(".cb" + projectid).each(function () {
                if ($(this).is(":visible") && this.checked) {
                    wordList.push(this.value);
                }
            });

            $.post(apiUrl, {'q': 'v1/project/' + projectid + "/action/add_good_words", 'words': JSON.stringify(wordList)}).then(function () {
                return $.getJSON(apiUrl, {'q': 'v1/project/' + projectid + "/action/good_word_suggestions"});
            }).done(function (data) {
                // now reload the table
                var tableSelector = "#tab_" + projectid;
                $(tableSelector).html(drawTable(data, projectid));
                setFreqCutoff(tableSelector);
            });
        },

        setFreqCutoff: setFreqCutoff
    };
});
