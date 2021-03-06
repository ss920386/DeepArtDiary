// Get the modal
var modal = document.getElementById('myModal');

// Get the <span> element that closes the modal
var span = document.getElementById("Modal_close");

// Get the modal content
var pic = document.getElementById('new_pic');
var time = document.getElementById('timestamp');
var content = document.getElementById('diary_content');
var mood = document.getElementById('diary_mood');

// When the user clicks on the button, open the modal 
function popUp(s,t,p,m) {
	console.log("mood: "+m);
    modal.style.display = "block";
    pic.src = s;
	time.innerHTML =t;
	content.innerHTML = p;
	mood.innerHTML=m;
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}