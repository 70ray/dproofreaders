/*global document $ */

var textControl;

$(function () {
    "use strict";
    var startPos;
    var endPos;
    var scrollTop;
    var theText;
    var myText;

    function initialise() {
        startPos = focusedItem.selectionStart;
        endPos = focusedItem.selectionEnd;
        scrollTop = focusedItem.scrollTop;
        theText = focusedItem.value;
        myText = theText.substring(startPos, endPos);
    }

    function setCaret(cPos) {
        focusedItem.selectionStart = cPos;
        focusedItem.selectionEnd = cPos;
    }

    function finalise(tagOpen, tagClose) {
        var subst;
        if (myText.slice(-1) === " ") { // exclude ending space char, if any
            subst = tagOpen + myText.slice(0, -1) + tagClose + " ";
        } else {
            subst = tagOpen + myText + tagClose;
        }
        focusedItem.value = theText.slice(0, startPos) + subst + theText.slice(endPos);
        focusedItem.focus();
        var cPos = startPos + (tagOpen.length + myText.length + tagClose.length);
        setCaret(cPos);
        focusedItem.scrollTop = scrollTop;
    }

    function isFootnoteLabel(label) {
        // A string is a footnote label if it's a letter A-Z, or an integer > 0
        if (label.length === 1 && "abcdefghijklmnopqrstuvwxyz".indexOf(label.toLowerCase()) !== -1) {
            return true;
        }
        return parseInt(label) === label && label > 0;
    }


    function insertTags(tagOpen, tagClose, replace) {
        function processText() {
            // Used when wrapping body text in markup or tags.
            // Modify the opening and closing tags and body text depending
            // on the context to make editing easier for the user.
            // Return updated tags and body.
            // If there's no selected text:
            // * Illustration markup may appear w/o a title, so remove the ': '.
            // * Formatting markup is redundant w/o any content, so don't produce it.
            if (myText === '') {
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
                var i = myText.indexOf(' ');
                if (i !== -1) {
                    var first = myText.substr(0, i);
                    if (isFootnoteLabel(first)) {
                        label = ' ' + first;
                        myText = myText.substr(i + 1);
                    }
                }
                tagOpen = tagOpen.replace(' #', label);
                // If there's no body text, remove the label entirely.
                if (myText === '') {
                    tagOpen = tagOpen.replace(': ', '');
                }
            }
        }

        initialise();
        if (replace) {
            myText = '';
        }
        processText();
        finalise(tagOpen, tagClose);
    }

    function lc_common(str) {
        var words = str.split(' ');
        var i;
        var common_lc_words =
                ':At:Under:Near:Upon:By:Of:In:On:For' + // prepositions
                ':Is:Was:Are' +    // 'small' verbs
                ':But:And:Or' +    // conjunctions
                ':A:An:The' +      // articles
                ':Am:Pm:Bc:Ad' +   // small caps abbreviations
                ':De:Van:La:Le:';  // LOTE

        // Start at i=1 to avoid changing the first word (leave it Titlecased).
        // E.g. if str is "A Winter's Tale", we don't want to lowercase the "A".
        for (i = 1; i < words.length; i += 1) {
            // If the word appears in the :-delimited list above, it should be lower case
            if (common_lc_words.indexOf(':' + words[i] + ':') !== -1) {
                words[i] = words[i].toLowerCase();
            }
        }
        return words.join(' ');
    }

    function title_case(str) {
        str = str.toLowerCase();
        var newStr = '';
        var i;
        for (i = 0; i < str.length; i += 1) {
            // Capitalise the first letter, or anything after a space, newline or period.
            if (i === 0 || ' \n.'.indexOf(str.charAt(i - 1)) !== -1) {
                newStr += str.charAt(i).toUpperCase();
            } else {
                newStr += str.charAt(i);
            }
        }
        newStr = lc_common(newStr);
        return newStr;
    }

    textControl = {
        insertText: function (insertion) {
            if (focusedItem) {
                insertTags(insertion, '', true);
                // for wordcheck input boxes
                $(focusedItem).trigger("input");
            }
        },

        focusText: function () {
            if (focusedItem) {
                focusedItem.focus();
            }
        },

        setCaret: function (cPos) {
            if (focusedItem) {
                setCaret(cPos);
            }
        },

        surroundSelection: function (wOT, wCT) {
            if (focusedItem) {
                insertTags(wOT, wCT, false);
            }
        },

        transformText: function (transformType) {
            if (!focusedItem) {
                return;
            }
            initialise();
            switch (transformType) {
            case 'title-case':
                myText = title_case(myText);
                break;
            case 'upper-case':
                myText = myText.toUpperCase();
                break;
            case 'lower-case':
                myText = myText.toLowerCase();
                break;
            case 'remove_markup':
                myText = myText.replace(/<\/?([ibfg]|sc)>/gi, '');
                break;
            default:
                break;
            }
            finalise('', '');
        },

        replaceAllText: function (newText) {
            if (focusedItem) {
                focusedItem.value = newText;
            }
        }
    };
});
