/*global $ imageUrl viewImageTextUrl*/
$(function () {

    var selector = document.getElementById("page-select");
    var prevButton = document.getElementById("prev-button");
    var nextButton = document.getElementById("next-button");

    function showPage() {
        var currentIndex = selector.selectedIndex;
        prevButton.disabled = (currentIndex <= 0);
        nextButton.disabled = (currentIndex >= (selector.length - 1));
        $("#image").attr("src", imageUrl + selector.value);
    }

    showPage();

    $(selector).change(showPage);

    $(prevButton).click(function () {
        selector.selectedIndex -= 1;
        showPage();
    });

    $(nextButton).click(function () {
        selector.selectedIndex += 1;
        showPage();
    });

    $("#show-text").click(function () {
        window.location.assign(viewImageTextUrl + selector.value);
    });
});
