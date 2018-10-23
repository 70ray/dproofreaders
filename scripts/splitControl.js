/*global document window
*/
function initSplit(verticalSplit, splitRatio) {
    'use strict';
    var splitPos,     // position of split
        topBar,
        pane1,
        dragBar,
        pane2,
        botBar,
        topHeight, // height of top bar
        minSiz, // minimum size for either side of split
        minPos, // ninimum position of split
        maxPos;   // maximum position of split

    function move_split() {
        var ppx;
        var ppx6;
        if (splitPos < minPos) {
            splitPos = minPos;
        }
        if (splitPos > maxPos) {
            splitPos = maxPos;
        }
        ppx = splitPos + "px";
        ppx6 = (splitPos + 6) + "px";
        if (verticalSplit) {
            pane1.style.width = ppx;
            dragBar.style.left = ppx;
            pane2.style.left = ppx6;
        } else {
            pane1.style.height = (splitPos - topHeight) + "px";
            dragBar.style.top = ppx;
            pane2.style.top = ppx6;
        }
    }

    function reLayout() {
        // height of top bar (with px suffix)
        var toppx = window.getComputedStyle(topBar, null).height;
        // height of bottom bar (with px suffix)
        var botpx = window.getComputedStyle(botBar, null).height;
        var botHeight = parseInt(botpx); // height of bottom bar
        topHeight = parseInt(toppx);
        var winWidth = window.innerWidth;
        var winHeight = window.innerHeight;
        var range; // splittable width or height
        var base; // edge of splitttable area
        pane1.style.top = toppx;
        pane2.style.bottom = botpx;
        if (verticalSplit) {
            range = winWidth;
            base = 0;
            // unset height or it would override bottom
            pane1.style.height = dragBar.style.height = "auto";
            pane1.style.bottom = dragBar.style.bottom = botpx;
            dragBar.style.top = toppx;
            dragBar.style.cursor = "ew-resize";
            dragBar.style.width = "6px";
            pane2.style.top = toppx;
        } else {
            range = winHeight - topHeight - botHeight;
            base = topHeight;
            pane1.style.width = "100%";
            dragBar.style.left = "0";
            dragBar.style.width = "100%";
            dragBar.style.cursor = "ns-resize";
            dragBar.style.height = "6px";
            pane2.style.left = "0";
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

    function dragBarMouseDown(event) {
        event.preventDefault();
        document.addEventListener("mousemove", windowMouseMove, false);
        document.addEventListener("mouseup", windowMouseUp, false);
        // if there is an iframe it will take mousemove
        pane2.style.pointerEvents = "none";
        pane1.style.pointerEvents = "none";
    }

    function windowMouseUp() {
        document.removeEventListener("mousemove", windowMouseMove, false);
        document.removeEventListener("mouseup", windowMouseUp, false);
        // restore normal operation
        pane2.style.pointerEvents = "auto";
        pane1.style.pointerEvents = "auto";
    }

    minSiz = 50;
    topBar = document.getElementById("topbar");
    botBar = document.getElementById("botbar");
    pane1 = document.getElementById("pane_1");
    dragBar = document.getElementById("dragbar");
    pane2 = document.getElementById("pane_2");
    reLayout();
    dragBar.addEventListener("mousedown", dragBarMouseDown, false);
    window.addEventListener("resize", reLayout, false);

    return {
        setSplit: function (vertical) {
            verticalSplit = vertical;
            reLayout();
        },

        reLayout: reLayout
    };
}
