/*global document window */

var textControl;

function initTextControl() {
    "use strict";
    textControl = (function () {
        var textArea = document.getElementById("text_area");

        function setCaret(cPos) {
            textArea.selectionStart = cPos;
            textArea.selectionEnd = cPos;
        }

        function insertTags(tagOpen, tagClose, replace) {
            var startPos = textArea.selectionStart;
            var endPos = textArea.selectionEnd;
            var scrollTop = textArea.scrollTop;
            var theText = textArea.value;
            var myText = theText.substring(startPos, endPos);
            if (replace) {
                myText = '';
            }
            var subst;
//            proc = processText(tagOpen,tagClose,myText);
//            tagOpen = proc[0];
//            tagClose = proc[1];
//            myText = proc[2];

            if (myText.slice(-1) === " ") { // exclude ending space char, if any
                subst = tagOpen + myText.slice(0, -1) + tagClose + " ";
            } else {
                subst = tagOpen + myText + tagClose;
            }
            textArea.value = theText.slice(0, startPos) + subst + theText.slice(endPos);
            textArea.focus();

            var cPos = startPos + (tagOpen.length + myText.length + tagClose.length);
            setCaret(cPos);
            textArea.scrollTop = scrollTop;
        }

    console.log(textArea.selectionStart);

        return {
            insertText: function (insertion) {
                insertTags(insertion, '', true);
            },

            focusText: function () {
                textArea.focus();
            },

            setCaret: setCaret
        };
    }());
}

window.addEventListener("DOMContentLoaded", initTextControl, false);
