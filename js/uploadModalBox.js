// Get the modal
var dmodal = document.getElementById('diaryModal');

// Get the <span> element that closes the modal
var dspan = document.getElementById("diaryModal_close");

// When the user clicks on the button, open the modal 
function dpopUp() {
    dmodal.style.display = "block";
}

function dclose() {
    dmodal.style.display = "none";
}

// When the user clicks on <span> (x), close the modal
dspan.onclick = function() {
    dmodal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == dmodal) {
        dmodal.style.display = "none";
    }
    if (event.target == pmodal) {
        pmodal.style.display = "none";
    }
}

// Get the modal
var pmodal = document.getElementById('picModal');

// Get the <span> element that closes the modal
var pspan = document.getElementById("picModal_close");

// When the user clicks on the button, open the modal 
function ppopUp() {
    dmodal.style.display = "none";
    pmodal.style.display = "block";
}

function pclose() {
    pmodal.style.display = "none";
}

// When the user clicks on <span> (x), close the modal
pspan.onclick = function() {
    pmodal.style.display = "none";
}

