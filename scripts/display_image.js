/*global document window Image projectsUrl alert codeUrl imageFile messages
 $ apiUrl projectID */

var displayControl;
$(function () {
    "use strict";
    var scanImage = document.getElementById("scanimage");
    var imageUrl;
    var imageArray;
    var currentIndex;
    var maxIndex;
    var selector = document.getElementById("jumpto");
    var prevButton = document.getElementById('prev-button');
    var nextButton = document.getElementById('next-button');
    var img = new Image();

    function prefetch() {
        // the images get saved in the browser cache
        if (currentIndex > 0) {
            img.src = imageUrl + imageArray[currentIndex - 1];
        }
        if (currentIndex < maxIndex) {
            img.src = imageUrl + imageArray[currentIndex + 1];
        }
    }

    function showImage() {
        prevButton.disabled = (0 === currentIndex);
        nextButton.disabled = (maxIndex === currentIndex);
        var imgFile = imageArray[currentIndex];
        scanImage.src = imageUrl + imgFile;
        scanImage.alt = imgFile;
    }

    function setup2(data) {
        console.log(data);
        imageArray = [];
        data.pages.forEach(function (item) {
            imageArray.push(item.image);
        });
        if (imageArray.length === 0) {
            prevButton.disabled = true;
            nextButton.disabled = true;
            alert(messages.noImages);
            return;
        }
        // populate image selector
        imageArray.forEach(function (image) {
            var opt = document.createElement("option");
            opt.value = image;
            opt.text = image;
            selector.add(opt);
        });
        currentIndex = imageArray.indexOf(imageFile);
        if (currentIndex < 0) {
            alert(messages.absentImage.replace("%s", imageFile));
            currentIndex = 0;
            imageFile = imageArray[0];
        }
        selector.selectedIndex = currentIndex;
        maxIndex = imageArray.length - 1;
        $(scanImage).on("load", prefetch);
        showImage();
    }

    function setup1(data) {
        console.log(data);
        var project = data.project;
        // set the return link
        var returnLink = document.getElementById('return-link');
        if (returnLink) {
            returnLink.href = codeUrl + "project.php?id=" + project.projectid;
            returnLink.innerHTML = messages.returnToProject.replace("%s", project.nameofwork);
        }
        // set the image url
        imageUrl = projectsUrl + project.projectid + "/";
        // get the images
        $.get(apiUrl, {"q": "v1/project/" + project.projectid + "/action/listpages", "fields": ["image"]}, setup2);
    }

    $.get(apiUrl, {'q': 'v1/project/' + projectID + "/action/listdata"}, setup1);

    displayControl = {
        setSize: function () {
            var percent = parseInt(document.getElementById("percent").value);
            if ((10 < percent) && (percent < 1000)) {
                scanImage.style.width = (10 * percent) + 'px';
            }
        },

        selectImage: function (selector) {
            currentIndex = selector.selectedIndex;
            showImage();
        },

        prevImage: function () {
            if (currentIndex > 0) {
                currentIndex -= 1;
                selector.selectedIndex = currentIndex;
                showImage();
            }
        },

        nextImage: function () {
            if (currentIndex < maxIndex) {
                currentIndex += 1;
                selector.selectedIndex = currentIndex;
                showImage();
            }
        }
    };
});
