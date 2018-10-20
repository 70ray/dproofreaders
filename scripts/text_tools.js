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

        function isFootnoteLabel(label) {
            // A string is a footnote label if it's a letter A-Z, or an integer > 0
            if (label.length === 1 && "abcdefghijklmnopqrstuvwxyz".indexOf(label.toLowerCase()) !== -1) {
                return true;
            }
            return parseInt(label) === label && label > 0;
        }

        // Used when wrapping body text in markup or tags.
        // Modify the opening and closing tags and body text depending
        // on the context to make editing easier for the user.
        // Return updated tags and body.
        function processText(tagOpen, tagClose, bodyText) {
            // If there's no selected text:
            // * Illustration markup may appear w/o a title, so remove the ': '.
            // * Formatting markup is redundant w/o any content, so don't produce it.
            if (bodyText === '') {
                if (tagOpen === '[Illustration: ') {
                    tagOpen = '[Illustration';
                } else if (tagOpen[0] === '<') {
                    tagOpen = '';
                    tagClose = '';
                }
            }
            // Handle footnote label substitution
            if (tagOpen === '[Footnote #: ') {
                // Split the selected text on the first space in the string.
                // If the first part is a label use it in the opening tag in
                // place of '#', otherwise remove the ' #' from the opening tag.
                var label = '';
                var i = bodyText.indexOf(' ');
                if (i !== -1) {
                    var first = bodyText.substr(0, i);
                    if (isFootnoteLabel(first)) {
                        label = ' ' + first;
                        bodyText = bodyText.substr(i + 1);
                    }
                }
                tagOpen = tagOpen.replace(' #', label);
                // If there's no body text, remove the label entirely.
                if (bodyText === '') {
                    tagOpen = tagOpen.replace(': ', '');
                }
            }
            return [tagOpen, tagClose, bodyText];
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
            var proc = processText(tagOpen, tagClose, myText);
            tagOpen = proc[0];
            tagClose = proc[1];
            myText = proc[2];

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

        return {
            insertText: function (insertion) {
                insertTags(insertion, '', true);
            },

            focusText: function () {
                textArea.focus();
            },

            setCaret: setCaret,

            surroundSelection: function (wOT, wCT) {
                insertTags(wOT, wCT, false);
            }
        };
    }());
}

window.addEventListener("DOMContentLoaded", initTextControl, false);
