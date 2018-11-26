/*global document sprintf alert codeUrl messages $ apiUrl confirm proofControl focusedItem */

const WC_TEXT = 0, WC_WORLD = 1;

var wordCheck;
$(function () {
    "use strict";
    var textArea = $("#text_area");
    var wordCheckPre = $("#wordcheck-pre");
    var origText; // text to use for word check
    // remember accepted words so they will be marked as accepted if we re-enter wordcheck
    // but we don't want to submit the same words again so keep another set of
    // new accepted words to submit.
    var acceptedWords = [];
    var newAcceptedWords = [];
    var pageChanged = false;
    var acceptImage = "<img src='" + codeUrl + "graphics/Book-Plus-Small.gif'>";
    var chunks; // the raw text data from server
    var languages = [];  // array of languages used
    var langSelector = document.getElementById("lang_select");

    function attr_safe(string) {
        return string.replace(/'/g, "&#039;");
    }

    function drawLangSelector(data) {
        $("#lang-data").html(sprintf(messages.dictionariesUsed, languages.join(', ')));

        while (langSelector.length) {
            langSelector.remove(0);
        }

        data.languages.forEach(function (language) {
            var opt = document.createElement("option");
            opt.value = language;
            opt.text = language;
            if (languages.indexOf(language) !== -1) {
                opt.selected = true;
            }
            langSelector.add(opt);
        });
//        console.log(data);
    }

    function applyWordCheck(data) {
        var puncRegex = /[\.,;\:\?\!\*\/\(\)#@%\+\=\[\]\{\}\<\>\\"\$\|_¬¢£¥©®§°±¶·´¸º×¦¡¿\-»«¯÷¹²³¼½¾¤]/g;
        var badWordMessages = data.messages;
        if (badWordMessages.length) {
            var messageText = '';
            badWordMessages.forEach(function (message) {
                messageText += message + "\n";
            });
            alert(messageText);
        }

        var wcText = '';
        chunks = data.chunks;
        languages = data.languages;
        var chunk, index, flag, textBoxLen, wordLen, changeFunction;

        for (index = 0; index < chunks.length; index += 1) {
            chunk = chunks[index];
            flag = data.flags[index];
            if (WC_TEXT === flag) {
                wcText += chunk.replace(puncRegex, "<span class='hl'>$&</span>");
                continue;
            }
            if ((WC_WORLD === flag) && (acceptedWords.indexOf(chunk)) !== -1) {
                wcText += "<span class='aw'>" + chunk + "</span>";
                continue;
            }
            // set the size of the edit box
            // note: in some browsers the edit box is not wide enough
            // for longer words, hence the scaling mechanism
            wordLen = chunk.length;
            textBoxLen = wordLen + Math.max(1 + Math.round(wordLen / 5), 2);
            if (WC_WORLD === flag) {
                wcText += "<span>";
                changeFunction = "wordCheck.disableAW(this);";
            } else {
                changeFunction = "wordCheck.evaluateWordChange(this);";
            }
            wcText += "<input type='text' onfocus='wordCheck.onFocus(this);' index='" + index + "' size='" +
                    textBoxLen + "' value='" + attr_safe(chunk) + "' oninput='" + changeFunction + "'>";
            // if the AW button is wanted, add the closing span and the button
            if (WC_WORLD === flag) {
                wcText += "<button type='button' class='transparent-button' onClick=\"wordCheck.acceptWord(this);\" title='" + attr_safe(messages.unflag) + "'>" + acceptImage + "</button></span>";
            }
        }
        wordCheckPre.html(wcText);
        wordCheckPre.show();
        $.getJSON(apiUrl, {'q': 'v1/langwithdict'}, drawLangSelector);
    }

    function quitWCDone() {
        wordCheckPre.hide();
        $(".WC-only").hide();
        proofControl.enableProof();
    }

    function quitWC() {
        // record that wordcheck has been done and any suggested words
        $.post(apiUrl, {'q': proofControl.projectPagePath() + "/action/save_wordcheck_event", 'accepted-words': JSON.stringify(newAcceptedWords)}, quitWCDone);
        // remember in case we enter wordcheck again
        acceptedWords = acceptedWords.concat(newAcceptedWords);
        // clear so we don't submit them again
        newAcceptedWords = [];
    }

    function wcApplyCorrections() {
        var index;
        $("input", wordCheckPre).each(function () {
            index = this.getAttribute("index");
//                console.log(index, this.value);
            chunks[index] = this.value;
        });
        origText = chunks.join("");
        // let's not record corrections in wc event since they aren't used so far as I can tell.
    }

    function getWordCheckData() {
        $.post(apiUrl, {'q': proofControl.projectPagePath() + "/action/wordcheck", 'text-data': origText, 'languages': JSON.stringify(languages)}, applyWordCheck);
    }

    wordCheck = {
        enter: function () {
            origText = textArea.val();
            pageChanged = false;
            $(".WC-only").css("display", "inline");
            // don't send accepted words, they will come back as bad again but we can mark them as aw
            getWordCheckData();
        },

        // submit suggested words but forget corrections
        quit: function () {
            if (pageChanged) {
                if (!confirm(messages.confirmExit)) {
                    return;
                }
            }
            quitWC();
        },

        applyCorrections: function () {
            wcApplyCorrections();
            textArea.val(origText);
            quitWC();
        },

        rerunAuxLang: function () {
            wcApplyCorrections();
            languages = [];
            $("option", langSelector).filter(":selected").each(function () {
                languages.push(this.value);
            });
            getWordCheckData();
        },

        acceptWord: function (theButton) {
            var word = $(theButton).prev().val();
            $("span").each(function () {
                if ($("input", this).val() === word) {
                    $(this).html(word).addClass("aw");
                }
            });
            newAcceptedWords.push(word);
        },

        disableAW: function (theInput) {
            var theButton = $(theInput).next();
            // has it changed?
            if (theInput.value !== theInput.defaultValue) {
                pageChanged = true;
                theButton.prop("disabled", true);
                theButton.html("<img src='" + codeUrl + "graphics/Book-Plus-Small-Disabled.gif'>");
            } else {
                theButton.prop("disabled", false);
                theButton.html(acceptImage);
            }
        },

        evaluateWordChange: function (theInput) {
            if (theInput.value !== theInput.defaultValue) {
                pageChanged = true;
            }
        },

        onFocus: function (theInput) {
            focusedItem = theInput;
        }
    };
});
