/*global document window $
*/
var splitControl;
$(function () {
    'use strict';
    var verticalSplit = 0;
    var splitRatio = 0.5;
    var splitPos,     // position of split
        pane1 = $("#pane_1"),
        dragBar = $("#dragbar"),
        pane2 = $("#pane_2"),
        topHeight, // height of top bar
        minSiz, // minimum size for either side of split
        minPos, // ninimum position of split
        maxPos;   // maximum position of split
    var range; // splittable width or height

    function move_split() {
        if (splitPos < minPos) {
            splitPos = minPos;
        }
        if (splitPos > maxPos) {
            splitPos = maxPos;
        }
        var sp6 = (splitPos + 6);
        var firstSize = splitPos;
        if (verticalSplit) {
            pane1.css("width", splitPos);
            dragBar.css("left", splitPos);
            pane2.css("left", sp6);
        } else {
            firstSize -= topHeight;
            pane1.css("height", firstSize);
            dragBar.css("top", splitPos);
            pane2.css("top", sp6);
        }
        splitRatio = firstSize / range;
    }

    function reLayout() {
        // height of top bar
        topHeight = parseInt($("#topbar").css("height"));
        // height of bottom bar
        var botHeight = parseInt($("#botbar").css("height"));
        var winWidth = window.innerWidth;
        var winHeight = window.innerHeight;
        var base; // edge of splitttable area
        pane1.css("top", topHeight);
        pane2.css("bottom", botHeight);
        if (verticalSplit) {
            range = winWidth;
            base = 0;
            // unset height or it would override bottom
            pane1.css("height", "auto");
            dragBar.css("height", "auto");
            pane1.css("bottom", botHeight);
            dragBar.css("bottom", botHeight);
            dragBar.css("top", topHeight);
            dragBar.css("cursor", "ew-resize");
            dragBar.css("width", 6);
            pane2.css("top", topHeight);
        } else {
            range = winHeight - topHeight - botHeight;
            base = topHeight;
            pane1.css("width", "100%");
            dragBar.css("left", 0);
            dragBar.css("width", "100%");
            dragBar.css("cursor", "ns-resize");
            dragBar.css("height", 6);
            pane2.css("left", 0);
        }
        splitPos = base + (range * splitRatio);
        minPos = base + minSiz;
        maxPos = base + range - minSiz;
        move_split();
    }

    function windowMouseMove(event) {
        splitPos = (verticalSplit
            ? event.pageX
            : event.pageY);
        move_split();
    }

    function windowMouseUp() {
        $(document).unbind("mousemove mouseup");
        // restore normal operation
        pane2.css("pointerEvents", "auto");
        pane1.css("pointerEvents", "auto");
    }

    function dragBarMouseDown(event) {
        event.preventDefault();
        $(document).mousemove(windowMouseMove).mouseup(windowMouseUp);
        // if there is an iframe it will take mousemove
        pane2.css("pointerEvents", "none");
        pane1.css("pointerEvents", "none");
    }

    minSiz = 50;
    reLayout();
    dragBar.mousedown(dragBarMouseDown);
    $(window).resize(reLayout);

    splitControl = {
        setSplit: function (vertical, ratio) {
            verticalSplit = vertical;
            splitRatio = ratio;
            reLayout();
        },

        reLayout: reLayout,
        getRatio() {
            return splitRatio;
        }
    };
});
