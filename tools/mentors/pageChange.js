/*global $ */
$(function () {
    "use strict";
    var selector = document.getElementById("page-select");
    var prevButton = document.getElementById("prev-button");
    var nextButton = document.getElementById("next-button");

    $(selector).change(function() {
        this.form.submit();
    });

    $(prevButton).click(function () {
        selector.selectedIndex -= 1;
        this.form.submit();
    });

    $(nextButton).click(function () {
        selector.selectedIndex += 1;
        this.form.submit();
    });

    var currentIndex = selector.selectedIndex;
    prevButton.disabled = (currentIndex <= 0);
    nextButton.disabled = (currentIndex >= (selector.length - 1));
});
