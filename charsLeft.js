
window.addEventListener("DOMContentLoaded", start);
			
function start()
{
	document.getElementById("twist").addEventListener("keyup", updateRemainingChars);
}
			
function updateRemainingChars()
{
	var twistChars = document.getElementById("twist").value;
	var charsLeft = document.getElementById("charsLeft");
	var remaining = 150 - twistChars.length;

	if (remaining < 20) {
		charsLeft.style.color = "red";
	}
	else {
		charsLeft.style.color = "green";
	}

	charsLeft.innerHTML = remaining;
}