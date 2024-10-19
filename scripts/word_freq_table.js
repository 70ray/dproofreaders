window.addEventListener("DOMContentLoaded", function () {
    function checkAllVisible() {
        const checkBoxes = document.getElementsByClassName(`cb_${this.dataset.instance}`);
        for (const checkBox of checkBoxes) {
            const tBody = checkBox.closest("tbody");
            if (getComputedStyle(tBody).display !== "none") {
                checkBox.checked = true;
            }
        }
    }

    const checkAllVisLinks = document.getElementsByClassName("check_all");
    for (const checkAllVisLink of checkAllVisLinks) {
        checkAllVisLink.addEventListener("click", checkAllVisible);
    }

    function unCheckAll() {
        const checkBoxes = document.getElementsByClassName(`cb_${this.dataset.instance}`);
        for (const checkBox of checkBoxes) {
            checkBox.checked = false;
        }
    }

    const unCheckAllLinks = document.getElementsByClassName("un_check_all");
    for (const unCheckAllLink of unCheckAllLinks) {
        unCheckAllLink.addEventListener("click", unCheckAll);
    }

    function setCutoff() {
        const newCutoff = this.dataset.cutoff;
        const tableBodies = document.getElementsByClassName("table_body");
        for (tableBody of tableBodies) {
            if (Number(tableBody.dataset.freqCutoff) < Number(newCutoff)) {
                tableBody.style.display = "none";
            } else {
                tableBody.style.display = "";
            }
        }
        document.getElementById("current_cutoff").innerHTML = newCutoff;
        // persist cutoff after submit for show_good_word_suggestions.php
        if (document.getElementById("freqCutoffValue")) {
            document.getElementById("freqCutoffValue").value = newCutoff;
        }
    }

    const cutoffLinks = document.getElementsByClassName("cut_off_link");
    for (const cutoffLink of cutoffLinks) {
        cutoffLink.addEventListener("click", setCutoff);
    }
});
