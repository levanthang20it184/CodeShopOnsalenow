function init() {
    SXE.initElementDialog('abbr');
    if (SXE.currentAction == "update") {
        SXE.showRemoveButton();
    }
}

function insertAbbr() {
    SXE.insertElement('abbr');
    tinyMCEPopup.close();
}

function removeAbbr() {
    SXE.removeElement('abbr');
    tinyMCEPopup.close();
}

tinyMCEPopup.onInit.add(init);
