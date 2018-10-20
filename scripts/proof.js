/*global document window Image projectsUrl alert codeUrl imageFile messages
 $ apiUrl projectID projState imageID pageState confirm */

var proofControl;
function initProofControl() {
    "use strict";
    proofControl = (function () {
        var scanImage = document.getElementById("scanimage");
        var textArea = document.getElementById("text_area");
        var imageUrl;

/*        function setRevertButton() {
            // the page state can only be out or temp
            // there is a text button and an icon button
            var revertButtons = document.querySelectorAll(".revert_button");
            var canRevert = document.getElementById('proofdiv').getAttribute('data-can_revert');
            var disableButton = ('0' === canRevert)
                ? true
                : false;
            var i = 0;
            while (i < revertButtons.length) {
                revertButtons[i].disabled = disableButton;
                i += 1;
            }
        }*/
/*        var imageArray;
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

        function setup2(pages) {
            imageArray = [];
            pages.forEach(function (item) {
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
            scanImage.addEventListener("load", prefetch);
            showImage();
        }*/

        function setPageState(data) {
            pageState = data.pageState;
            // there is a text button and an icon button
            var revertButtons = document.querySelectorAll(".revert_button");
            // must end with 'temp' or 'out'
            var disableButton = (pageState.slice(-3) === "out");
            var i = 0;
            while (i < revertButtons.length) {
                revertButtons[i].disabled = disableButton;
                i += 1;
            }
        }

        function loadState(data) {
            setPageState(data);
        }

        function loadText(data) {
    console.log(data);
            setPageState(data);
            textArea.value = data.text;
            textArea.focus();
            textControl.setCaret(0);
        }

        function loadImageText(data) {
    console.log(data);
            imageID = data.imageID;
            scanImage.src = imageUrl + data.imageID;
            scanImage.alt = data.imageID;
            loadText(data);
        }

        function toProjectPage() {
            window.location.replace(codeUrl + "project.php?id=" + projectID + "&expected_state=" + projState);
        }

        function projectPagePath() {
            return 'v1/project/' + projectID + "/state/" + projState + "/page/" + imageID + "/state/" + pageState;
        }

        imageUrl = projectsUrl + projectID + "/";
        if (imageID) {
            // check out a done or inprogress page
            $.get(apiUrl, {'q': projectPagePath() + "/checkoutpage"}, loadImageText);
        } else {
            // checkout a new page
            $.post(apiUrl, {'q': 'v1/project/' + projectID + "/state/" + projState + "/checkoutnextpage"}, loadImageText);
        }

        return {
            revertToOriginal: function () {
                if (confirm(messages.confirmRevertOrig)) {
                    $.post(apiUrl, {'q': projectPagePath() + "/reverttoorig", 'text-data': textArea.value}, loadText);
                }
            },

            revertToLastSave: function () {
                if (confirm(messages.confirmRevertToLastSave)) {
                    $.get(apiUrl, {'q': projectPagePath() + "/reverttolastsave"}, loadText);
                }
            },

            saveAsInProgress: function () {
                $.post(apiUrl, {'q': projectPagePath() + "/saveasinprogress", 'text-data': textArea.value}, loadState);
            },

            saveAndDoNext: function () {
                $.post(apiUrl, {'q': projectPagePath() + "/saveanddonext", 'text-data': textArea.value}, loadImageText);
            },

            saveAsDone: function () {
                $.post(apiUrl, {'q': projectPagePath() + "/saveasdone", 'text-data': textArea.value}, toProjectPage);
            },

            returnPage: function () {
                if (confirm(messages.confirmReturn)) {
                    $.get(apiUrl, {'q': projectPagePath() + "/returnpage"}, toProjectPage);
                }
            },

            stopProof: function () {
                if (confirm(messages.confirmStop)) {
                    toProjectPage();
                }
            }

//        }

/*            // set the return link
            var returnLink = document.getElementById('return-link');
            if (returnLink) {
                returnLink.href = codeUrl + "project.php?id=" + project.projectid;
                returnLink.innerHTML = messages.returnToProject.replace("%s", project.nameofwork);
            }
            // set the image url
            imageUrl = projectsUrl + project.projectid + "/";
            // get the images
            $.get(apiUrl, {"q": "v1/project/" + project.projectid + "/pages", "fields": ["image"]}, setup2);
        }*/

//        $.get(apiUrl, {'q': 'v1/project/' + projectID}, setup1);

/*        return {
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
            }*/
        };
    }());
}

window.addEventListener("DOMContentLoaded", initProofControl, false);
