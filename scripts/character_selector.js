/*global document window */

var initialCode;
var picker;
function initPicker() {
    "use strict";
    picker = (function () {
        var charSelector = $("#char-selector");
        var largeChar = document.getElementById("large_char");

        function enableBoard(newCode) {
            // hide the visible key block
            $('.show', charSelector).removeClass('show');
            // show the new one
            $('.' + newCode, charSelector).addClass('show');
            // mark the new selected tab
            $('.selected-tab', charSelector).removeClass('selected-tab');
            $('#id_' + newCode, charSelector).addClass('selected-tab');
            largeChar.value = ''; // remove old character
            textControl.focusText();
        }

        function loadKb(kbData) {
//    console.log(kbData);
            var initialSet = false;
            var initialCode;
            var rowString = '';
            var selectorString = "<div id='selector_row'>";
            function drawRow(charRow) {
                rowString += "<div>";
                var chars = charRow.split(' ');
                chars.forEach(function (character) {
                    rowString += "<input type='button' class='picker' value='";
                    if('0' === character) {
                        character = ''; // empty string
                    }
                    rowString += character + "'>";
                });
                rowString += "</div>\n";
            }
            kbData.forEach(function(item) {
//    console.log(item);
                var code = item.code;
                if(!initialSet) {
                    initialSet = true;
                    initialCode = code;
                }
                selectorString += "<input type='button' id='id_" + code + "' class='selector_button' value='" + code + "'>";
                rowString += "<div class='" + code + " key-block'>";
                drawRow(item.upper);
                drawRow(item.lower);
                rowString += "</div>\n";
            });
            selectorString += "</div>\n";
//           console.log(selectorString + rowString);
           charSelector.html(selectorString + rowString);
           enableBoard(initialCode);
            $(".selector_button", charSelector).click(function() {
//            console.log(this.value, this.classList);
                enableBoard(this.value);
            });

            $(".picker", charSelector).click(function() {
                textControl.insertText(this.value);
            })
            .mouseover(function() {
                largeChar.value = this.value;
            })
            .each(function() {
                if(keyTitles.hasOwnProperty(this.value)) {
                    this.title = keyTitles[this.value];
                }
            });
        }

        return {
            showChar: function (ch) {
                largeChar.value = ch;
            },

            enableBoard: enableBoard,
            loadKb: loadKb
        };
    }());
}

window.addEventListener("DOMContentLoaded", initPicker, false);
