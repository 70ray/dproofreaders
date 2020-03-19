/*global $ initSplit */
$(function () {
    "use strict";
    var mainSplit;
    mainSplit = initSplit("pane_container", 0, 60, 50, 50);
    $(window).resize(mainSplit.reLayout);
});
