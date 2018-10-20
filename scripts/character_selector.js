/*global document window */

var initialCode;
var picker;
function initPicker() {
    "use strict";
    picker = (function () {
        var charSelector = document.getElementById("char-selector");
        var largeChar = document.getElementById("large_char");

        function enableBoard(newCode) {
            var rows = charSelector.getElementsByClassName('row-show');
            var i;
            while (rows.length > 0) {
                rows[0].classList.remove('row-show');
            }
            rows = charSelector.getElementsByClassName(newCode);
            for (i = 0; i < rows.length; i += 1) {
                rows[i].classList.add('row-show');
            }
            var oldTabs = charSelector.getElementsByClassName('selected-tab');
            while (oldTabs.length > 0) {
                oldTabs[0].classList.remove('selected-tab');
            }
            document.getElementById('id_' + newCode).classList.add('selected-tab');
            largeChar.value = ''; // remove old character
            textControl.focusText();
        }

        if (initialCode) {
            enableBoard(initialCode);
        }

        return {
            showChar: function (ch) {
                largeChar.value = ch;
            },

            enableBoard: enableBoard
        };
    }());
}

window.addEventListener("DOMContentLoaded", initPicker, false);
