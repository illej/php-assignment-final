/* Initialize */
window.onload = function() {
	loadHTML('init', initialize);
	loadHTML('users', userList);
	setTimeout(putAtBottom, 2500);
}
function loadHTML(input, func) {
	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			func(this);
		}
	};
	xhr.open('GET', 'controller.php?' + input, true);
	xhr.send();
}
function initialize(xhr) {
	document.getElementById('data').innerHTML = xhr.responseText;
}
function userList(xhr) {
	document.getElementById('user-list').innerHTML = xhr.responseText;
}
function updateMsgs(xhr) {
	console.log(xhr.responseText);
	document.getElementById('msgs').innerHTML = xhr.responseText;
}

/* Send Message */
var go = function() {
	console.log(">Go'ed.");
	var input = document.getElementById('input').value;
	loadHTML(input, updateMsgs);
	
	$("#msgs").ready(function() {
		//setTimeout(putAtBottom, 2100);
		putAtBottom();
	});
}

/* Clear form inputs */
function resetForm() {
    document.getElementById('input').value = '';
}

/* jQuery Enhancements */
function putAtBottom() {
	$('#msgs').stop().animate({ 
			scrollTop: $('#msgs')[0].scrollHeight
		},
		200
	);
};

function checkAtBottom(){
	var scrollheight;
	var scrollpos;
	var scrollzero;
	scrollheight = $('#msgs')[0].scrollHeight;
	scrollpos = $('#msgs').scrollTop();
	scrollzero = scrollheight - scrollpos;
	if(scrollzero < 360){
		putAtBottom();
	}
};

function refreshMsgs(){
	$('#msgs').load('refreshMsgs.php');
	$('#user-list').load('refreshUsrs.php');
};

function refresh()
{
	refreshMsgs();
	checkAtBottom();
};

setInterval(refresh, 2000);